<?php
namespace Coalmine\Logger;

/**
 * Log to KLogger.
 * 
 * @link https://github.com/katzgrau/KLogger
 */
class KLogger implements \Coalmine\Logger {
    /** @var KLogger */
    protected $logger;
    
    /**
     * Constructor.
     * 
     * @param KLogger $logger Logger instance
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
        return $this->logger->logCrit($message);
    }
    
    /**
     * Log an error message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function error($message, array $context = array()) {
        return $this->logger->logError($message);
    }
    
    /**
     * Log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function warning($message, array $context = array()) {
        return $this->logger->logWarn($message);
    }
    
    /**
     * Log an info message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function info($message, array $context = array()) {
        return $this->logger->logInfo($message);
    }
    
    /**
     * Log a debug message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function debug($message, array $context = array()) {
        return $this->logger->logDebug($message);
    }
}