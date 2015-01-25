<?php

class Text
{
    private static $texts;

    public static function get($key)
    {
        if (!self::$texts) {
            self::$texts = require('../application/config/texts.php');
        }

        return self::$texts[$key];
    }

}
