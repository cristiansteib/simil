<?php

abstract class Matcher {
    public $url_pattern;
    private $url_pretty;

    function __construct($url_pattern) {
        $this->url_pattern = $this->escape_dash_characters($url_pattern);
        $this->url_pattern = '/^' . $this->url_pattern  . '[\/]?(?(?=\?).|$)/';
        $this->url_pretty = $url_pattern;
    }

    function getUrlPattern(){
        return $this->url_pattern;
    }
    
    function getUrl(){
        return $this->url_pretty;
    }

    function escape_dash_characters($string){
        return str_replace("/", "\/", $string);
    }

    abstract function isThis($url_request);

    abstract function getParameters($url_request);

}