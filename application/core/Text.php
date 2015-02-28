<?php

class Text
{
    private static $texts;

    public static function get($key)
    {
	    // if not $key
	    if (!$key) {
		    return null;
	    }

	    // load config file (this is only done once per application lifecycle)
        if (!self::$texts) {
            self::$texts = require('../application/config/texts.php');
        }

	    // check if array key exists
	    if (!array_key_exists($key, self::$texts)) {
		    return null;
	    }

        return self::$texts[$key];
    }

}
