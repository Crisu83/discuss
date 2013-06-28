<?php

Yii::import('ext.coalmine.components.*');

class CoalmineErrorHandler extends CErrorHandler {
	/**
	 * @var string the Coalmine application signature.
	 */
	public $signature;
	/**
	 * @var array the connection configuration (key=>value).
	 */
	public $connection = array();
	/**
	 * @var array items to filter out from the sent data.
	 */
	public $filters = array('password');
	/**
	 * @var array custom variables to send with notifications.
	 */
	public $customVariables = array();
	/**
	 * @var string the application version.
	 */
	public $version = '1.0.0';
	/**
	 * @var integer the trace depth for the errors reported (defaults to 2).
	 */
	public $traceDepth = 2;

	/** @var CoalmineConnection */
	protected $_connection;

	/**
	 * Initializes the component.
	 */
	public function init() {
		parent::init();
		if (!isset($this->connection['class'])) {
			$this->connection['class'] = 'CoalmineConnection';
		}
		$this->_connection = Yii::createComponent($this->connection);
		Yii::app()->attachEventHandler('onEndRequest', array($this, 'onShutdown'));
	}

	/**
	 * @param $exception
	 * @param array $options
	 * @return bool
	 */
	public function notify($exception, $options = array()) {
		try {
			$options['signature'] = $this->signature;
			$options['framework'] = 'Yii';
			$options['version'] = $this->version;
			$options['customVariables'] = $this->customVariables;
			$options['filters'] = $this->filters;
			$notification = CoalmineNotification::createFromException($exception, $options);
			$success = $this->_connection->sendNotification($notification);
		} catch (Exception $e) {
			$success = false;
		}
		return $success;
	}

	/**
	 *
	 */
	public function onShutdown() {
		$error = $this->_getLastError();
		if ($error && (int)$error['type'] === E_ERROR && $this->_isReportable($error['type'])) {
			$error = CoalmineError::create($error['type'], $error['message'], $error['file'], $error['line'], $this->traceDepth);
			$this->notify($error, array('severity' => $error->getSeverity()));
		}
	}

	/**
	 * @param CErrorEvent $event
	 */
	protected function handleError($event) {
		if ($this->_isReportable($event->code)) {
			$error = CoalmineError::create($event->code, $event->message, $event->file, $event->line, $this->traceDepth);
			$this->notify($error, array('severity' => $error->getSeverity()));
		}
		parent::handleError($event);
	}

	/**
	 * @param Exception $exception
	 */
	protected function handleException($exception) {
		$this->notify($exception);
		parent::handleException($exception);
	}

	/**
	 * @param $code
	 * @return int
	 */
	protected function _isReportable($code) {
		return $code & error_reporting();
	}

	/**
	 * @return array|null
	 */
	protected function _getLastError() {
		return function_exists('error_get_last') ? error_get_last() : null;
	}
}