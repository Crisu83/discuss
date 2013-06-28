<?php
namespace Coalmine\Logger;

/**
 * Log to Zend_Log.
 * 
 * @link http://framework.zend.com/manual/en/zend.log.html
 */
class ZendLog implements \Coalmine\Logger {
    /** @var Zend_Log|\Zend\Log\Logger */
    protected $logger;
    
    /**
     * Constructor.
     * 
     * @param Zend_Log|\Zend\Log\Logger $logger Logger instance
     */
    public function __construct($logger) {
        $this->logger = $logger;
    }
    
    /**
     * Log a critical message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return void
     */
    public function critical($message, array $context = array()) {
        return $this->logger->crit($message);
    }
    
    /**
     * Log an error message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return void
     */
    public function error($message, array $context = array()) {
        return $this->logger->err($message);
    }
    
    /**
     * Log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return void
     */
    public function warning($message, array $context = array()) {
        return $this->logger->warn($message);
    }
    
    /**
     * Log an info message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return void
     */
    public function info($message, array $context = array()) {
        return $this->logger->info($message);
    }
    
    /**
     * Log a debug message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return void
     */
    public function debug($message, array $context = array()) {
        return $this->logger->debug($message);
    }
}