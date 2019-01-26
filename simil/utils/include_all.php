<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 11/11/18
 * Time: 17:44
 */

function include_all_php_of_directory($directory) {
    $files = scandir($directory);
    foreach ($files as $filename) {
        if (preg_match('/php$/', $filename)) {
            $path = $directory . $filename;
            include_once($path);
        }
    }
}