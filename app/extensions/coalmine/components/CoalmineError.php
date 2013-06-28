<?php

class CoalmineError extends CComponent {
	// Error severities.
	const SEVERITY_ERROR = 'error';
	const SEVERITY_INFO = 'info';
	const SEVERITY_WARNING = 'warning';

	/**
	 * @var string
	 */
	protected $_message;
	/**
	 * @var integer
	 */
	protected $_code;
	/**
	 * @var string
	 */
	protected $_file;
	/**
	 * @var integer
	 */
	protected $_line;
	/**
	 * @var array
	 */
	protected $_trace;

	/**
	 * Creates an error.
	 * @param $code
	 * @param $message
	 * @param $file
	 * @param $line
	 * @param $context
	 * @param $trace
	 */
	function __construct($code, $message, $file, $line, $trace) {
		$this->_code = $code;
		$this->_message = $message;
		$this->_file = $file;
		$this->_line = $line;
		$this->_trace = $trace;
		// todo: add support for context
	}

	/**
	 * @return string
	 */
	public function getSeverity() {
		// Since PHP 5.3.0
		if ((defined("E_DEPRECATED") && $this->_code == E_DEPRECATED)
			|| (defined("E_USER_DEPRECATED") && $this->_code == E_USER_DEPRECATED)) {
			return self::SEVERITY_INFO;
		}
		// Since PHP 5.2.0
		if (defined("E_RECOVERABLE_ERROR") && $this->_code == E_RECOVERABLE_ERROR) {
			return self::SEVERITY_ERROR;
		}
		switch ($this->_code) {
			case E_WARNING:
			case E_USER_WARNING:
			case E_NOTICE:
			case E_USER_NOTICE:
				return self::SEVERITY_WARNING;
			case E_STRICT:
				return self::SEVERITY_INFO;
			case E_ERROR:
			case E_USER_ERROR:
			default:
				return self::SEVERITY_ERROR;
		}
	}

	/**
	 * @return string
	 */
	public function getTraceAsString() {
		$traceAsString = array();
		$i = 0;
		foreach ($this->_trace as $i => $frame) {
			if (isset($frame['file']) && isset($frame['line'])) {
				$file = $frame['file'] . '(' . $frame['line'] . '): ';
			} else {
				$file = '';
			}

			if (isset($frame['class'])) {
				$function = $frame['class'] . $frame['type'] . $frame['function'];
			} else {
				$function = $frame['function'];
			}

			if (isset($frame['args'])) {
				$args = array_map(
					array($this, '_normalizeTraceArgument'),
					$frame['args']
				);
				$args = implode(', ', $args);
			} else {
				$args = '';
			}

			$traceAsString[] = '#' . $i . ' ' . $file . '.' . $function . '(' . $args . ')';
		}
		$i++;
		$traceAsString[] = '#' . $i . '{main}';
		return implode("\n", $traceAsString);
	}

	/**
	 * @param $arg
	 * @return string
	 */
	protected function _normalizeTraceArgument($arg) {
		if (is_object($arg)) {
			return vsprintf("Object(%s)", get_class($arg));
		} else {
			return (string) $arg;
		}
	}

	/**
	 * @return string
	 */
	public function getFile() {
		return $this->_file;
	}

	/**
	 * @return int
	 */
	public function getLine() {
		return $this->_line;
	}

	/**
	 * @return int
	 */
	public function getCode() {
		return $this->_code;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->_message;
	}

	/**
	 * @return array
	 */
	public function getTrace() {
		return $this->_trace;
	}

	/**
	 * @param $code
	 * @param $message
	 * @param $file
	 * @param $line
	 * @param $traceDepth
	 * @return CoalmineError
	 */
	public static function create($code, $message, $file, $line, $traceDepth) {
		$trace = array_slice(debug_backtrace(), $traceDepth);
		return new CoalmineError($code, $message, $file, $line, $trace);
	}
}