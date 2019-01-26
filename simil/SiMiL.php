<?php

/**
 * Framework loader
 */
define('FW_CODE_ROOT', dirname(__FILE__));
require_once("utils/time.php");
define('START_REQUEST_MICROTIME', microtime_float());

session_start();
require_once ("dao/autoload.php");
require_once ("model/autoload.php");
require_once("url_dispatcher/Dispatcher.php");


/**
 * Protection mechanism for CSRF,
 * is configurable via SETTINGS
 * and set globally the token
 */
$is_active_csrf_protection = SETTINGS['middleware']['csrf_protection']['enabled'] ?? true;

if ( $is_active_csrf_protection ){
    require_once("middleware/CSRF/CSRF.php");
    $csrf = new ProtectorCSRF();
    $csrf->aggressiveProtectRequestMethods();
    define('CSRF_TOKEN', $csrf->getCSRFToken());
}

$is_active_xss_protection =  SETTINGS['middleware']['xss_protection']['enabled'] ?? true;
if ( $is_active_xss_protection ) {
    require_once("middleware/XSS/XSS.php");
}


/**
 * Use the urls defined by the user.
 */

require_once(CODE_ROOT . '/urls.php');
$dispatcher = new Dispatcher();
$dispatcher->setUrls(get_urls());

/**
 * Process the request when the user call this function.
 * So the user can do other stuff before and after execute the 
 * desired controller.
 */
function run() {
    global $dispatcher;
    return $dispatcher->run($_SERVER['REQUEST_URI']);
}


