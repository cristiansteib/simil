<?php
class NotFound404Exception extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        http_response_code(404);
        echo "Error 404: " . $message;
        die();
    }
}