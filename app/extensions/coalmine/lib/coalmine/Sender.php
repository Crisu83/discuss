<?php
namespace Coalmine;

class Sender {
    protected $headers = array(
        "Content-Type" => "application/x-www-form-urlencoded",
        "Accept"       => "text/json, application/json"
    );
    
    protected $config;
    
    public function __construct($config) {
        $this->config = $config;
    }
    
    public function send($notification) {
        $logger = $this->config->logger;
        
        if (! in_array($this->config->environment, $this->config->enabledEnvironments)) {
            $logger->info("Attempted to send a notification to Coalmine, but "
                . "notifications are not enabled for {$this->config->environment}. "
                . "To send requests for this environment, add it to "
                . "config.enabled_environments.");
            return false;
        }
        
        $cacheData = $this->config->cacheData;
        if (isset($cacheData["retry_after_uts"])) {
            $retryAfter = $cacheData["retry_after_uts"] - time();
            if ($retryAfter > 0) { // Throttle has expired
                $this->logTooManyRequests($retryAfter);
                return false;
            } else {
                unset($cacheData["retry_after_uts"]);
                // This introduces a race condition, but it should be OK
                $this->config->cacheData = $cacheData;
            }
        }
        
        $url = $this->assembleUrl($notification);
        $success = $this->doSend($url, $notification);
        
        if ($success) {
            $logger->info("Sent notification to Coalmine at $url");
        }
        
        return $success;
    }
    
    protected function doSend($url, $notification) {
        $data = http_build_query($notification->getPostData());
        $curl = $this->openConnection();
        $response = $this->dispatch($curl, $url, $data);
        $success = $this->handleResponse($curl, $response);
        $this->closeConnection($curl);
        
        return $success;
    }
    
    protected function openConnection() {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->config->httpOpenTimeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->config->httpReadTimeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        if (is_array($this->config->curlOptions)) {
            foreach ($this->config->curlOptions as $option => $value) {
                curl_setopt($curl, $option, $value);
            }
        }
        
        return $curl;
    }
    
    protected function dispatch($curl, $url, $data) {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        return curl_exec($curl);
    }
    
    protected function handleResponse($curl, $response) {
        $logger = $this->config->logger;
        $success = false;
        
        if ($response === false) {
            $error = curl_error($curl);
            $info = curl_getinfo($curl);
            $url = $info["url"];
            $logger->error("Timeout while attempting to notify Coalmine at $url: $error");
        } else {
            $responseCode = $this->getHttpCode($curl);
            switch ($responseCode) {
            case 200: // OK
                $success = true;
                break;
            case 429: // Too Many Requests
                $headers = $this->getHttpHeaders($response);
                if (isset($headers["Retry-After"])) {
                    $retryAfter = (int) $headers["Retry-After"];
                } else {
                    $retryAfter = 0;
                }
                $this->logTooManyRequests($retryAfter);
                $cacheData = $this->config->cacheData;
                $cacheData["retry_after_uts"] = time() + $retryAfter;
                // This introduces a race condition, but it should be OK
                $this->config->cacheData = $cacheData;
                break;
            default:
                $logger->error("Unable to notify Coalmine (HTTP $responseCode)");
                if ($response) {
                    $logger->error("Coalmine response: $response");
                }
            }
        }
        
        return $success;
    }
    
    protected function getHttpHeaders($response) {
        $responseParts = explode("\r\n\r\n", $response);
        list($rawHeaders) = array_slice($responseParts, -2, 1);
        $rawHeadersArray = explode("\r\n", $rawHeaders);
        array_shift($rawHeadersArray);
        $headers = array();
        foreach ($rawHeadersArray as $rawHeader) {
            list($key, $value) = preg_split("/\s*:\s*/", $rawHeader);
            $headers[$key] = $value;
        }
        return $headers;
    }
    
    protected function getHttpCode($curl) {
        return curl_getinfo($curl, CURLINFO_HTTP_CODE);
    }
    
    protected function closeConnection($curl) {
        curl_close($curl);
    }
    
    protected function assembleUrl($notification) {
        $config = $this->config;
        $path = $notification->getResourcePath();
        return "{$config->protocol}://{$config->host}:{$config->port}{$path}";
    }
    
    protected function logTooManyRequests($retryAfter) {
        $this->config->logger->warning(
            "Too many requests to Coalmine for this plan, will retry after "
            . "$retryAfter seconds");
    }
}