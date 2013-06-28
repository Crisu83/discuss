<?php
namespace Coalmine\Logger;

/**
 * Log to PEAR's Log package.
 * 
 * @link https://github.com/pear/Log
 */
class PEARLog implements \Coalmine\Logger {
    /** @var Log */
    protected $logger;
    
    /**
     * Constructor.
     * 
     * @param Log $logger Logger instance
     */
    public function __construct($logger) {
        $this->logger = $logger;
    }
    
    /**
     * Log a critical message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function critical($message, array $context = array()) {
        return $this->logger->crit($message);
    }
    
    /**
     * Log an error message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function error($message, array $context = array()) {
        return $this->logger->err($message);
    }
    
    /**
     * Log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function warning($message, array $context = array()) {
        return $this->logger->warning($message);
    }
    
    /**
     * Log an info message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function info($message, array $context = array()) {
        return $this->logger->info($message);
    }
    
    /**
     * Log a debug message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function debug($message, array $context = array()) {
        return $this->logger->debug($message);
    }
}