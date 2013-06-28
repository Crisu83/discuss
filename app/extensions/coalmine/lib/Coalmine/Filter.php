<?php
namespace Coalmine;

class Filter {
    const REPLACEMENT_TEXT = "[FILTERED]";
    
    protected $filters;
    
    public function __construct($filters) {
        $this->filters = $filters;
    }
    
    public function filter($assoc) {
        foreach ($this->filters as $filter) {
            array_walk_recursive($assoc, array($this, "doFilter"), $filter);
        }
        return $assoc;
    }
    
    public function doFilter(&$value, $key, $filter) {
        if (stripos($key, $filter) !== false) {
            $value = self::REPLACEMENT_TEXT;
        }
    }
}