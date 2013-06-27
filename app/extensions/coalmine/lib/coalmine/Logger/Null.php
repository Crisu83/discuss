<?php
namespace Coalmine\Logger;

/**
 * Log to...nowhere!
 */
class Null implements \Coalmine\Logger {
    /**
     * Pretend to log a critical message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return string Message that was supplied
     */
    public function critical($message, array $context = array()) {
        return $message;
    }
    
    /**
     * Pretend to log an error message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return string Message that was supplied
     */
    public function error($message, array $context = array()) {
        return $message;
    }
    
    /**
     * Pretend to log a warning message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return string Message that was supplied
     */
    public function warning($message, array $context = array()) {
        return $message;
    }
    
    /**
     * Pretend to log an info message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return string Message that was supplied
     */
    public function info($message, array $context = array()) {
        return $message;
    }
    
    /**
     * Pretend to log a debug message.
     * 
     * @param string $message Message
     * @param array $context Context (unused)
     * @return string Message that was supplied
     */
    public function debug($message, array $context = array()) {
        return $message;
    }
}