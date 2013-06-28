<?php
namespace Coalmine\Logger;

/**
 * Log to Monolog.
 * 
 * @link https://github.com/Seldaek/monolog
 */
class Monolog implements \Coalmine\Logger {
    /** @var \Monolog\Logger */
    protected $logger;
    
    /**
     * Constructor.
     * 
     * @param \Monolog\Logger $logger Logger instance
     */
    public function __construct($logger) {
        $this->logger = $logger;
    }
    
    /**
     * Log a critical message.
     * 
     * @param string $message Message
     * @param array $context Context
     * @return bool True on success
     */
    public function critical($message, array $context = array()) {
        return $this->logger->crit($message, $context);
    }
    
    /**
     * Log an error message.
     * 
     * @param string $message Message
     * @param array $context Context
     * @return bool True on success
     */
    public function error($message, array $context = array()) {
        return $this->logger->err($message, $context);
    }
    
    /**
     * Log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context
     * @return bool True on success
     */
    public function warning($message, array $context = array()) {
        return $this->logger->warn($message, $context);
    }
    
    /**
     * Log an info message.
     * 
     * @param string $message Message
     * @param array $context Context
     * @return bool True on success
     */
    public function info($message, array $context = array()) {
        return $this->logger->info($message, $context);
    }
    
    /**
     * Log a debug message.
     * 
     * @param string $message Message
     * @param array $context Context
     * @return bool True on success
     */
    public function debug($message, array $context = array()) {
        return $this->logger->debug($message, $context);
    }
}