<?php

class Session {

    private $user_id;
    private $user_data;

    public function get_or_set($key, $value) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        $_SESSION[$key] = $value;
        return $value;
    }

    public function __construct() {
        $this->user_id = $this->get_or_set('USER_ID', null);
        $this->user_data = $this->get_or_set('USER_DATA', []);
    }

    private function sync_var_with_session(&$var, $keySession, $value){
        $_SESSION[$keySession] = $value;
        $var = $value;
    }

    public function isAuthenticated() {
        return $this->user_id !== null;
    }

    /**
     * Is to represent when a single user is authenticated
     * @param $user_id int
     * @param $user_data
     */
    public function createAuthenticatedSession($user_id, $user_data) {
        $this->sync_var_with_session($this->user_id, 'USER_ID', $user_id);
        $this->sync_var_with_session( $this->user_data, 'USER_DATA', $user_data);
    }

    public function destroyAuthenticatedSession() {
        $this->sync_var_with_session($this->user_id, 'USER_ID', null);
        $this->sync_var_with_session( $this->user_data, 'USER_DATA', null);
        session_destroy();
    }

    public function userId() {
        return $this->user_id;
    }

}