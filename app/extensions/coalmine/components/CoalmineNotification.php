<?php

class CoalmineNotification extends CComponent {
	const REPLACEMENT_TEXT = '[FILTERED]';

	/**
	 * @var string
	 */
	public $signature;
	/**
	 * @var string
	 */
	public $framework;
	/**
	 * @var string
	 */
	public $environmentName;
	/**
	 * @var string
	 */
	public $version;
	/**
	 * @var string
	 */
	public $file;
	/**
	 * @var int
	 */
	public $line;
	/**
	 * @var string
	 */
	public $message;
	/**
	 * @var string
	 */
	public $trace;
	/**
	 * @var array
	 */
	public $url;
	/**
	 * @var string
	 */
	public $ipAddress;
	/**
	 * @var string
	 */
	public $userAgent;
	/**
	 * @var string
	 */
	public $method;
	/**
	 * @var string
	 */
	public $cookies;
	/**
	 * @var string
	 */
	public $parameters;
	/**
	 * @var string
	 */
	public $referrer;
	/**
	 * @var array
	 */
	public $environment;
	/**
	 * @var array
	 */
	public $server;
	/**
	 * @var string
	 */
	public $severity;
	/**
	 * @var string
	 */
	public $hostname;
	/**
	 * @var int
	 */
	public $processId;
	/**
	 * @var array
	 */
	public $customVariables = array();
	/**
	 * @var array
	 */
	public $filters = array();
	/**
	 * @var integer
	 */
	public $userId;
	/**
	 * @var string
	 */
	public $defaultSeverity = CoalmineError::SEVERITY_ERROR;

	/**
	 * @param array $options
	 */
	public function __construct($options = array()) {
		// Extract the exception from the given options.
		if (isset($options['exception'])) {
			/* @var Exception $exception */
			$exception = $options['exception'];
			$this->trace = $exception->getTraceAsString();
			$this->message = $exception->getMessage();
			if (empty($this->message)) {
				$this->message = get_class($exception);
			}
			$this->file = $exception->getFile();
			$this->line = $exception->getLine();
			unset($options['exception']);
		}

		// Set custom variables if applicable.
		if (isset($options['customVariables'])) {
			$this->customVariables = $options['customVariables'];
			unset($options['customVariables']);
		}

		$this->_setFromRequest();
		$this->_setFromEnvironment();
		$this->_setFromServer();
		$this->_setFromYii();

		foreach ($options as $name => $value) {
			$this->$name = $value;
		}

		if (!isset($options['severity'])) {
			$this->severity = $this->defaultSeverity;
		}

		if (function_exists('gethostname')) {
			$this->hostname = gethostname();
		} else {
			$this->hostname = php_uname('n');
		}

		$pid = getmypid();
		if ($pid !== false) {
			$this->processId = $pid;
		}
	}

	/**
	 *
	 */
	protected function _setFromRequest() {
		$request = Yii::app()->getRequest();
		$this->url = $request->getRequestUri();
		$this->ipAddress = $request->getUserHostAddress();
		$this->userAgent = $request->getRequestUri();
		$this->method = $request->getRequestType();
		$this->parameters = $request->getQueryString();
		$this->referrer = $request->getUrlReferrer();
	}

	/**
	 *
	 */
	protected function _setFromEnvironment() {
		$environmentKeys = array(
			'argc',
			'argv',
			'HTTPS',
			'HTTP_ACCEPT',
			'HTTP_ACCEPT_CHARSET',
			'HTTP_ACCEPT_ENCODING',
			'HTTP_ACCEPT_LANGUAGE',
			'HTTP_CACHE_CONTROL',
			'HTTP_CONNECTION',
			'HTTP_COOKIE',
			'HTTP_HOST',
			'HTTP_REFERER',
			'HTTP_USER_AGENT',
			'HTTP_VERSION',
			'HTTP_X_REWRITE_URL',
			'IIS_WasUrlRewritten',
			'ORIG_PATH_INFO',
			'PATH_INFO',
			'PATH_TRANSLATED',
			'QUERY_STRING',
			'REMOTE_ADDR',
			'REMOTE_HOST',
			'REQUEST_METHOD',
			'REQUEST_PATH',
			'REQUEST_TIME',
			'REQUEST_URI',
			'SCRIPT_FILENAME',
			'SCRIPT_NAME',
			'UNENCODED_URL'
		);
		$this->environment = array();
		foreach ($environmentKeys as $key) {
			if (isset($_SERVER[$key])) {
				$this->environment[$key] = $_SERVER[$key];
			}
		}
	}

	/**
	 *
	 */
	protected function _setFromServer() {
		if (isset($_SERVER["HTTP_COOKIE"])) {
			$this->cookies = $_SERVER["HTTP_COOKIE"];
		}
		$serverKeys = array(
			'DOCUMENT_ROOT',
			'GATEWAY_INTERFACE',
			'SERVER_ADDR',
			'SERVER_NAME',
			'SERVER_PORT',
			'SERVER_PROTOCOL',
			'SERVER_SIGNATURE',
			'SERVER_SOFTWARE',
		);
		$this->server = array();
		foreach ($serverKeys as $key) {
			if (isset($_SERVER[$key])) {
				$this->server[$key] = $_SERVER[$key];
			}
		}
	}

	/**
	 *
	 */
	protected function _setFromYii() {
		$app = Yii::app();
		/* @var CWebUser $user */
		if (($user = $app->getComponent('user')) !== null) {
			$this->userId = $user->id;
		}
		/* @var CController $controller */
		if (($controller = $app->getController()) !== null) {
			$this->customVariables['controller'] = $controller->id;
			/* @var CAction $action */
			if (($action = $controller->getAction()) !== null) {
				$this->customVariables['action'] = $action->id;
			}
			/* @var CModule $module */
			if (($module = $controller->getModule()) !== null) {
				$this->customVariables['module'] = $module->id;
			}
		}
	}

	/**
	 * @return string
	 */
	public function createQueryString() {
		return http_build_query(array(
			'signature' => $this->signature,
			'json'		=> $this->toJSON(),
		));
	}

	/**
	 * @return string
	 * @see https://www.coalmineapp.com/docs
	 */
	public function toJSON() {
		$data = array(
			'version'         => $this->version,
			'app_environment' => $this->environmentName,
			'url'             => $this->url,
			'file'            => $this->file,
			'line_number'     => $this->line,
			'message'         => $this->message,
			'stack_trace'     => $this->trace,
			//'class'           => $this->errorClass, // todo: implement this if possible
			'framework'       => $this->framework,
			'parameters'      => $this->parameters,
			'ip_address'      => $this->ipAddress,
			'user_agent'      => $this->userAgent,
			'cookies'         => $this->cookies,
			'method'          => $this->method,
			'environment'     => $this->environment,
			'server'          => $this->server,
			'severity'        => $this->severity,
			'hostname'        => $this->hostname,
			'process_id'      => $this->processId,
			'referrer'        => $this->referrer,
			'user_id'         => $this->userId,
			'application'     => $this->customVariables
		);
		// todo: filter data.
		return json_encode($this->_filterData($data));
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function _filterData($data) {
		foreach ($this->filters as $filter) {
			array_walk_recursive($data, function(&$value, $key, $filter) {
				if (stripos($key, $filter) !== false) {
					$value = self::REPLACEMENT_TEXT;
				}
			}, $filter);
		}
		return $data;
	}

	/**
	 * @param $exception
	 * @param array $options
	 * @return CoalmineNotification
	 */
	public static function createFromException($exception, $options = array()) {
		$options['exception'] = $exception;
		return new CoalmineNotification($options);
	}
}