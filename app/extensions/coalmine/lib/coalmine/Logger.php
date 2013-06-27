<?php
namespace Coalmine;

interface Logger {
    public function critical($message, array $context = array());
    public function error($message, array $context = array());
    public function warning($message, array $context = array());
    public function info($message, array $context = array());
    public function debug($message, array $context = array());
}