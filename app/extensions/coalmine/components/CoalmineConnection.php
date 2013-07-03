<?php

class CoalmineConnection extends CComponent {
	const CACHE_KEY_PREFIX = 'CoalmineConnection';
	const LOG_CATEGORY = 'coalmine';
	const RESOURCE_NOTIFY = 'notify/';

	/**
	 * @var string the API host.
	 */
	public $host = 'https://coalmineapp.com';
	/**
	 * @var integer the port to use.
	 */
	public $port = 443;
	/**
	 * @var string the name of the active environment.
	 */
	public $environmentName;
	/**
	 * @var array list of names for environments in which coalmine will receive notifications.
	 */
	public $enabledEnvironments = array('production', 'staging');
	/**
	 * @var array additional options for cURL.
	 * @see http://php.net/manual/en/function.curl-setopt.php
	 */
	public $curlOptions = array();
	/**
	 * @var string the name of the caching component to use.
	 */
	public $cacheID = 'cache';

	public $headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		'Accept'       => 'text/json, application/json'
	);

	/**
	 * @return resource
	 */
	protected function _open() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		foreach ($this->curlOptions as $name => $value) {
			curl_setopt($curl, $name, $value);
		}
		return $curl;
	}

	/**
	 * @param CoalmineNotification $notification
	 * @return bool
	 */
	public function sendNotification($notification) {
		if (!in_array($this->environmentName, $this->enabledEnvironments)) {
			Yii::log('Attempted to send a notification to Coalmine, '
				. 'but notifications are not enabled for ' . $this->environmentName . '.'
				. 'To send requests for this environment, add it to enabledEnvironments.', CLogger::LEVEL_INFO, self::LOG_CATEGORY);
			return false;
		}
		if (($cache = $this->_getCache()) !== null) {
			$cacheKey = $this->_calculateCacheKey('retryAfter');
			if (($retryAfter = $cache->get($cacheKey)) !== false) {
				if ($retryAfter > 0) { // Throttle has expired
					$this->logTooManyRequests($retryAfter);
					return false;
				} else {
					$cache->delete($cacheKey);
				}
			}
		}
		$url = $this->_createUrlForResource(self::RESOURCE_NOTIFY);
		$data = $notification->createQueryString();
		$success = $this->sendRequest($url, $data);
		if ($success) {
			Yii::log('Sent notification to Coalmine at ' . $url, CLogger::LEVEL_INFO, self::LOG_CATEGORY);
		}
		return $success;
	}

	/**
	 * @param string $resource
	 * @return string
	 */
	protected function _createUrlForResource($resource) {
		return $this->host . ':' . $this->port . '/' . $resource;
	}

	/**
	 * @param string $url
	 * @param string $data
	 * @return boolean
	 */
	public function sendRequest($url, $data) {
		$curl = $this->_open();
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($curl);
		$success = $this->_handleResponse($curl, $response);
		$this->_close($curl);
		return $success;
	}

	/**
	 * @param resource $curl
	 * @param boolean $response
	 * @return boolean
	 */
	protected function _handleResponse($curl, $response) {
		$success = false;
		if ($response === false) {
			$error = curl_error($curl);
			$info = curl_getinfo($curl);
			$url = $info['url'];
			Yii::log('Timeout while attempting to notify Coalmine at ' . $url . ': ' . $error, CLogger::LEVEL_ERROR, self::LOG_CATEGORY);
		} else {
			$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			switch ($responseCode) {
				case 200: // OK
					$success = true;
					break;
				case 429: // Too Many Requests
					$headers = $this->_getHttpHeaders($response);
					if (isset($headers['Retry-After'])) {
						$retryAfter = (int)$headers['Retry-After'];
					} else {
						$retryAfter = 0;
					}
					//$this->logTooManyRequests($retryAfter);
					if (($cache = $this->_getCache()) !== null) {
						$cache->set($this->_calculateCacheKey('retryAfter'), time() + $retryAfter);
					}
					break;
				default:
					Yii::log('Unable to notify Coalmine (HTTP ' . $responseCode . ')', CLogger::LEVEL_ERROR, self::LOG_CATEGORY);
					if ($response) {
						Yii::log('Coalmine response: ' . $response, CLogger::LEVEL_ERROR, self::LOG_CATEGORY);
					}
			}
		}
		return $success;
	}

	/**
	 * @param $response
	 * @return array
	 */
	protected function _getHttpHeaders($response) {
		$responseParts = explode("\r\n\r\n", $response);
		list($rawHeaders) = array_slice($responseParts, -2, 1);
		$rawHeadersArray = explode("\r\n", $rawHeaders);
		array_shift($rawHeadersArray);
		$headers = array();
		foreach ($rawHeadersArray as $rawHeader) {
			list($key, $value) = preg_split("/\s*:\s*/", $rawHeader);
			$headers[$key] = $value;
		}
		return $headers;
	}

	/**
	 * @param $retryAfter
	 */
	protected function logTooManyRequests($retryAfter) {
		Yii::log('Too many requests to Coalmine for this plan, will retry after ' . $retryAfter . ' seconds.', CLogger::LEVEL_WARNING, self::LOG_CATEGORY);
	}

	/**
	 * @param $curl
	 */
	protected function _close($curl) {
		curl_close($curl);
	}

	/**
	 * @param $id
	 * @return string
	 */
	protected function _calculateCacheKey($id) {
		return self::CACHE_KEY_PREFIX . $id;
	}

	/**
	 * @return CCache
	 */
	protected function _getCache() {
		return ($cache = Yii::app()->getComponent($this->cacheID)) !== null ? $cache : null;
	}
}
