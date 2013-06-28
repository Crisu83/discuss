<?php
namespace Coalmine\Logger;

/**
 * Log to Analog.
 * 
 * @link https://github.com/jbroadway/analog
 */
class Analog implements \Coalmine\Logger {
    /**
     * Log a critical message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return mixed
     */
    public function critical($message, array $context = array()) {
        return Analog::log($message, Analog::CRITICAL);
    }
    
    /**
     * Log an error message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return mixed
     */
    public function error($message, array $context = array()) {
        return Analog::log($message, Analog::ERROR);
    }
    
    /**
     * Log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return mixed
     */
    public function warning($message, array $context = array()) {
        return Analog::log($message, Analog::WARNING);
    }
    
    /**
     * Log an info message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return mixed
     */
    public function info($message, array $context = array()) {
        return Analog::log($message, Analog::INFO);
    }
    
    /**
     * Log a debug message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return mixed
     */
    public function debug($message, array $context = array()) {
        return Analog::log($message, Analog::DEBUG);
    }
}