<?php
namespace Coalmine\Logger;

/**
 * Log to Apache log4php.
 * 
 * @link https://logging.apache.org/log4php/
 */
class Log4php implements \Coalmine\Logger {
    /** @var Logger */
    protected $logger;
    
    /**
     * Constructor.
     * 
     * @param Logger $logger Logger instance
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
        return $this->logger->fatal($message);
    }
    
    /**
     * Log an error message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function error($message, array $context = array()) {
        return $this->logger->error($message);
    }
    
    /**
     * Log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return bool True on success
     */
    public function warning($message, array $context = array()) {
        return $this->logger->warn($message);
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