<?php
namespace Coalmine;

class Error {
    protected $message;
    protected $code;
    protected $file;
    protected $line;
    protected $context;
    protected $trace;
    
    public function __construct(
            $type = 0, $message = "", $file = null, $line = null,
            array $context = array(), array $trace = array()) {
        $this->type = $type;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
        $this->context = $context;
        $this->trace = $trace;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getCode() {
        return $this->getType();
    }
    
    public function getSeverity() {
        // Since PHP 5.3.0
        if ((defined("E_DEPRECATED") && $this->type == E_DEPRECATED)
                || (defined("E_USER_DEPRECATED") && $this->type == E_USER_DEPRECATED)) {
            return "info";
        }
        
        // Since PHP 5.2.0
        if (defined("E_RECOVERABLE_ERROR") && $this->type == E_RECOVERABLE_ERROR) {
            return "error";
        }
        
        switch ($this->type) {
            case E_WARNING:
            case E_USER_WARNING:
            case E_NOTICE:
            case E_USER_NOTICE:
                return "warning";
            case E_STRICT:
                return "info";
            case E_ERROR:
            case E_USER_ERROR:
            default:
                return "error";
        }
    }
    
    public function getFile() {
        return $this->file;
    }
    
    public function getLine() {
        return $this->line;
    }
    
    public function getTrace() {
        return $this->trace;
    }
    
    public function getTraceAsString() {
        $traceAsString = array();
        $i = 0;
        foreach ($this->trace as $i => $frame) {
            if (isset($frame["file"]) && isset($frame["line"])) {
                $file = "{$frame["file"]}({$frame["line"]}): ";
            } else {
                $file = "";
            }
            
            if (isset($frame["class"])) {
                $function = $frame["class"] . $frame["type"] . $frame["function"];
            } else {
                $function = $frame["function"];
            }
            
            if (isset($frame["args"])) {
                $args = array_map(
                    array($this, "normalizeTraceArgument"), 
                    $frame["args"]
                );
                $args = implode(", ", $args);
            } else {
                $args = "";
            }
            
            $traceAsString[] = "#$i $file$function($args)";
        }
        $i++;
        $traceAsString[] = "#$i {main}";

        return implode("\n", $traceAsString);
    }
    
    public function getContext() {
        return $this->context;
    }
    
    protected function normalizeTraceArgument($arg) {
        if (is_object($arg)) {
            return vsprintf("Object(%s)", get_class($arg));
        } else {
            return (string) $arg;
        }
    }
}