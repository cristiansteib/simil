<?php
class Singleton
{
    private static $instance;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
          $clazz = get_called_class();
          self::$instance = new $clazz();
        }
        return self::$instance;
    }

}
