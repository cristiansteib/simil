<?php
/**
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */

function html_scape(&$value) {
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function filterArray($array) {
    array_walk_recursive($array, "html_scape");
    return $array;
}

$_POST = filterArray($_POST);
$_GET = filterArray($_GET);
