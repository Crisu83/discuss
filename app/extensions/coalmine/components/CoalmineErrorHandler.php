<?php

Yii::setPathOfAlias('Coalmine', __DIR__ . '/../lib/Coalmine');

class CoalmineErrorHandler extends CErrorHandler
{
	/**
	 * @var array the configuration (key=>value).
	 */
	public $config;
	/**
	 * @var array custom variables to send with notifications.
	 */
	public $customVariables = array();

	/** @var \Coalmine\Configuration */
	protected $_configuration;
	/** @var \Coalmine\Sender */
	protected $_sender;

	/**
	 * Initializes the component.
	 */
	public function init()
	{
		parent::init();
		$this->config['userId'] = $this->_resolveUserId();
		$this->configure($this->config);
	}

	/**
	 * @param $callableOrArray
	 */
	public function configure($callableOrArray)
	{
		$configuration = $this->_getConfiguration();
		if (is_array($callableOrArray))
		{
			foreach ($callableOrArray as $key => $value)
				$configuration->$key = $value;
		}
		else
			$callableOrArray($configuration);
	}

	/**
	 * @param $exception
	 * @param array $options
	 * @return bool
	 */
	public function notify($exception, $options = array())
	{
		try {
			$options['customVariables'] = $this->customVariables;
			$notification = \Coalmine\Notification::fromException($exception, $this->_getConfiguration(), $options);
			$success = $this->_getSender()->send($notification);
		} catch (Exception $e) {
			$success = false;
		}
		return $success;
	}

	/**
	 * @param CErrorEvent $event
	 */
	protected function handleError($event)
	{
		if ($this->_isReportable($event->code))
		{
			$error = $this->_exceptionFromError($event->code, $event->message, $event->file, $event->line);
			$this->notify($error, array('severity' => $error->getSeverity()));
		}
		parent::handleError($event);
	}

	/**
	 * @param Exception $exception
	 */
	protected function handleException($exception)
	{
		$this->notify($exception);
		parent::handleException($exception);
	}

	/**
	 * @return Coalmine_Configuration
	 */
	protected function _getConfiguration()
	{
		if (isset($this->_configuration))
			return $this->_configuration;
		else
			return $this->_configuration = new \Coalmine\Configuration;
	}

	protected function _getSender()
	{
		if (isset($this->_sender))
			return $this->_sender;
		else
			return $this->_sender = new \Coalmine\Sender($this->_getConfiguration());
	}

	/**
	 * @param $type
	 * @param $message
	 * @param null $file
	 * @param null $line
	 * @param array $context
	 * @return Coalmine_Error
	 */
	protected function _exceptionFromError($type, $message, $file = null, $line = null, $context = array())
	{
		$trace = array_slice(debug_backtrace(), 2);
		return new \Coalmine\Error($type, $message, $file, $line, $context, $trace);
	}

	/**
	 * @param $code
	 * @return int
	 */
	protected function _isReportable($code)
	{
		return error_reporting() & $code;
	}

	/**
	 * @return int
	 */
	protected function _resolveUserId()
	{
		/* @var CWebUser $user */
		if (($user = Yii::app()->getComponent('user')) !== null)
			return $user->id;
		return 0;
	}
}