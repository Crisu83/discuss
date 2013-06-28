<?php
namespace Coalmine;

class Notification {
    public $trace;
    public $message;
    public $lineNumber;
    public $url;
    public $errorClass;
    public $controller;
    public $action;
    public $method;
    public $parameters;
    public $ipAddress;
    public $userAgent;
    public $cookies;
    public $environment;
    public $server;
    public $severity;
    public $hostname;
    public $processId;
    public $file;
    public $referrer;
    public $threadId;
    public $customVariables;
    protected $config;
    
    public static function fromException($exception, $config, $options = array()) {
        $options["exception"] = $exception;
        return new self($config, $options);
    }
    
    public function __construct($config, $options = array()) {
        $this->config = $config;
        
        if (isset($options["exception"])) {
            $exception = $options["exception"];
            $this->trace = $exception->getTraceAsString();
            $message = $exception->getMessage();
            if ($message == "") {
                $this->message = get_class($exception);
            } else {
                $this->message = $exception->getMessage();
            }
            $this->file = $exception->getFile();
            $this->lineNumber = $exception->getLine();
            unset($options["exception"]);
        }
        
        if (isset($options["customVariables"])) {
            $this->customVariables = $options["customVariables"];
            unset($options["customVariables"]);
        }
        
        $this->setFromServer();
        
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
        
        if (! isset($options["severity"])) {
            $this->severity = "error";
        }
        
        if (function_exists("gethostname")) {
            $this->hostname = gethostname();
        } else {
            $this->hostname = php_uname("n");
        }
        
        $pid = getmypid();
        if ($pid !== false) {
            $this->processId = $pid;
        }
    }
    
    public function getPostData() {
        return array(
            "signature" => $this->config->signature,
            "json"      => $this->serialize()
        );
    }
    
    public function serialize() {
        $results = array(
            "version"         => $this->config->version, 
            "app_environment" => $this->config->environment,
            "url"             => $this->url,
            "file"            => $this->file,
            "line_number"     => $this->lineNumber,
            "message"         => $this->message, 
            "stack_trace"     => $this->trace, 
            "class"           => $this->errorClass, 
            "framework"       => $this->config->framework, 
            "parameters"      => $this->parameters, 
            "ip_address"      => $this->ipAddress, 
            "user_agent"      => $this->userAgent, 
            "cookies"         => $this->cookies, 
            "method"          => $this->method,
            "environment"     => $this->environment,
            "server"          => $this->server,
            "severity"        => $this->severity,
            "hostname"        => $this->hostname,
            "process_id"      => $this->processId,
            "referrer"        => $this->referrer,
            "user_id"         => $this->config->userId,
            "application"     => $this->customVariables
        );
        return json_encode($this->filter($results));
    }
    
    public function filter($assoc) {
        $filter = new \Coalmine\Filter($this->config->filters);
        return $filter->filter($assoc);
    }
    
    public function getResourcePath() {
        return "/notify/";
    }
    
    protected function setFromServer() {
        $this->url = $this->getUrl();
        $this->ipAddress = $this->getIpAddress();
        
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $this->userAgent = $_SERVER["HTTP_USER_AGENT"];
        }
        
        if (isset($_SERVER["REQUEST_METHOD"])) {
            $this->method = $_SERVER["REQUEST_METHOD"];
        }
        
        if (isset($_SERVER["HTTP_COOKIE"])) {
            $this->cookies = $_SERVER["HTTP_COOKIE"];
        }
        
        if (isset($_SERVER["QUERY_STRING"])) {
            $this->parameters = $_SERVER["QUERY_STRING"];
        }
        
        $environmentKeys = array(
            "argc",
            "argv",
            "HTTPS",
            "HTTP_ACCEPT",
            "HTTP_ACCEPT_CHARSET",
            "HTTP_ACCEPT_ENCODING",
            "HTTP_ACCEPT_LANGUAGE",
            "HTTP_CACHE_CONTROL",
            "HTTP_CONNECTION",
            "HTTP_COOKIE",
            "HTTP_HOST",
            "HTTP_REFERER",
            "HTTP_USER_AGENT",
            "HTTP_VERSION",
            "HTTP_X_REWRITE_URL",
            "IIS_WasUrlRewritten",
            "ORIG_PATH_INFO", 
            "PATH_INFO",
            "PATH_TRANSLATED",
            "QUERY_STRING", 
            "REMOTE_ADDR",
            "REMOTE_HOST", 
            "REQUEST_METHOD", 
            "REQUEST_PATH",
            "REQUEST_TIME",
            "REQUEST_URI",
            "SCRIPT_FILENAME", 
            "SCRIPT_NAME",
            "UNENCODED_URL"
        );
        $this->environment = array();
        foreach ($environmentKeys as $key) {
            if (isset($_SERVER[$key])) {
                $this->environment[$key] = $_SERVER[$key];
            }
        }
        
        $serverKeys = array(
            "DOCUMENT_ROOT",
            "GATEWAY_INTERFACE",
            "SERVER_ADDR",
            "SERVER_NAME",
            "SERVER_PORT",
            "SERVER_PROTOCOL",
            "SERVER_SIGNATURE",
            "SERVER_SOFTWARE",
        );
        $this->server = array();
        foreach ($serverKeys as $key) {
            if (isset($_SERVER[$key])) {
                $this->server[$key] = $_SERVER[$key];
            }
        }
    }
    
    protected function getUrlScheme() {
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            return "https";
        } else {
            return "http";
        }
    }
    
    protected function getIpAddress() {
        $ipAddress = "";
        
        if (! empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ipAddress = $_SERVER["HTTP_CLIENT_IP"];
        } else if (! empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (! empty($_SERVER["REMOTE_ADDR"])) {
            $ipAddress = $_SERVER["REMOTE_ADDR"];
        }
        
        return $ipAddress;
    }
    
    protected function getHttpHost() {
        if (! empty($_SERVER["HTTP_HOST"])) {
            return $_SERVER["HTTP_HOST"];
        }
        
        if (!empty($_SERVER["SERVER_NAME"]) && !empty($_SERVER["SERVER_PORT"])) {
            $name = $_SERVER["SERVER_NAME"];
            $port = $_SERVER["SERVER_PORT"];
            $scheme = $this->getUrlScheme();
            
            if (($scheme == "http" && $port == 80)
                    || ($scheme == "https" && $port == 443)) {
                $host = $name;
            } else {
                $host = "$name:$port";
            }
            
            return $host;
        }
        
        return "";
    }
    
    protected function getUrl() {
        $url = "";
        $schemeAndHost = $this->getUrlScheme() . "://" . $this->getHttpHost();
        
        // IIS 7 with Microsoft URL Rewrite Module
        if (! empty($_SERVER['HTTP_X_ORIGINAL_URL'])) {
            $requestUri = $_SERVER['HTTP_X_ORIGINAL_URL'];
        }
        
        // IIS with ISAPI_Rewrite
        if (! empty($_SERVER["HTTP_X_REWRITE_URL"])) {
            $url = $_SERVER["HTTP_X_REWRITE_URL"];
        
        // httpd, nginx, lighttpd, etc.
        } elseif (! empty($_SERVER["REQUEST_URI"])) {
            $url = $_SERVER["REQUEST_URI"];
            if (strpos($url, $schemeAndHost) === 0) {
                $url = substr($url, strlen($schemeAndHost));
            }
        
        // IIS 5, PHP as CGI
        } elseif (! empty($_SERVER["ORIG_PATH_INFO"])) {
            $url = $_SERVER["ORIG_PATH_INFO"];
        }
        
        if (! empty($_SERVER["QUERY_STRING"])) {
            $url = strtok($url, "?");
        }
        
        if (strlen($url) > 0 && $url[0] == "/") {
            $url = substr($url, 1);
        }
        
        return "$schemeAndHost/$url";
    }
}