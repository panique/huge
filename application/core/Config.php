<?php

class Config
{
    private static $config;

    public static function get($key)
    {
        if (!self::$config) {
            self::$config = require('../application/config/config.' . Environment::get() . '.php');
        }

        return self::$config[$key];
    }

}
