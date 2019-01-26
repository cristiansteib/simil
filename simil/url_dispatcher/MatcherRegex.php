<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 07/09/18
 * Time: 00:25
 */
include_once('Matcher.php');
class MatcherRegex extends Matcher{

    function isThis($url_request) {
        return preg_match($this->url_pattern, $url_request, $matches, PREG_OFFSET_CAPTURE);
    }

    function getParameters($url_request) {
        $ok = preg_match($this->url_pattern, $url_request, $matches);
        if ($ok){
            # el primer elemento no interesa, seria el path completo
            return array_slice($matches,1);
        }
        return [];
    }
}