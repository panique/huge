<?php

/**
 * This is under development. Expect changes!
 * Class Request
 */
class Request
{
    /**
     * gets/returns the value of a specific key of the POST super-global
     * @param mixed $key key
     * @return mixed the key's value or nothing
     */
    public static function post($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
    }

    /**
     * gets/returns the value of a specific key of the GET super-global
     * @param mixed $key key
     * @return mixed the key's value or nothing
     */
    public static function get($key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
    }
}
