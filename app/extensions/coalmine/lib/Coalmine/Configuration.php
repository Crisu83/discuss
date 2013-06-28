<?php
namespace Coalmine;

class Configuration {
    // Application options
    public $url;
    public $environment;
    public $version;
    public $signature;
    public $logger;
    public $projectRoot;
    public $framework;
    public $userId;

    // Coalmine options
    public $handleErrors;
    public $handleExceptions;
    public $useErrorReportingSetting;
    public $enabledEnvironments;
    public $filters;

    // HTTP options
    public $secure;
    public $host;
    public $port;
    public $httpUser;
    public $httpPassword;
    public $proxyHost;
    public $proxyPort;
    public $proxyUser;
    public $proxyPassword;
    public $httpOpenTimeout;
    public $httpReadTimeout;
    public $curlOptions; // Associative array of cURL options
                         // See http://php.net/manual/en/function.curl-setopt.php
    protected $protocol;
    protected $cacheData;
    
    public function __construct() {
        $this->protocol = "https";
        $this->host = "coalmineapp.com";
        $this->port = 443;
        $this->secure = true;
        $this->httpOpenTimeout = 3;
        $this->httpReadTimeout = 6;
        $this->handleErrors = true;
        $this->handleExceptions = true;
        $this->useErrorReportingSetting = true;
        $this->enabledEnvironments = array("production", "staging");
        $this->filters = array("password");
        $this->logger = new \Coalmine\Logger\Null;
    }
    
    public function __set($name, $value) {
        switch ($name) {
        case "cacheData":
            $this->setCacheData($value);
        case "protocol":
            $this->setProtocol($value);
        }
    }
    
    public function __get($name) {
        switch ($name) {
        case "cacheData":
            return $this->getCacheData();
        case "protocol":
            return $this->$name;
        }
    }
    
    public function setProtocol($protocol) {
        if (! in_array($protocol, array("http", "https"))) {
            $protocol = "http";
        }
        $this->protocol = $protocol;
    }
    
    public function getCacheData() {
        if ($this->cacheData === null) {
            $file = $this->getCacheFile();
            if (file_exists($file)) {
                if (! is_readable($file)) {
                    $this->logger->warning("Cache file at $file is not readable");
                    return null;
                }
                $cacheData = unserialize(file_get_contents($file));
                if ($cacheData === false) {
                    $this->logger->warning("Could not deserialize cache data at $file");
                    return null;
                }
                $this->cacheData = $cacheData;
            } else {
                $this->cacheData = array();
            }
        }
        return $this->cacheData;
    }
    
    public function setCacheData(array $cacheData) {
        file_put_contents($this->getCacheFile(), serialize($cacheData));
        $this->cacheData = $cacheData;
    }
    
    public function clearCache() {
        $file = $this->getCacheFile();
        if (file_exists($file)) {
            @unlink($this->getCacheFile());
        }
    }
    
    public function getCacheFile() {
        $path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
        return $path . DIRECTORY_SEPARATOR . "coalmine.cache";
    }
}