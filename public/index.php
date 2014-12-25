<?php

/**
 * A simple, clean and secure login / user authentication solution embedded into a small PHP framework.
 * Part of a set of login scripts, more on http://www.php-login.net
 *
 * PHP LOGIN (FRAMEWORK VERSION) 2.1 (in development)
 *
 * @author Panique
 * @link http://www.php-login.net/
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// The Composer auto-loader (official way to load Composer contents) to load external stuff automatically
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

// Load application config (error reporting, database credentials etc.)
require '../application/config/config.php';

// The auto-loader to load the php-login related internal stuff automatically
require '../application/config/autoload.php';

// Start our application
$app = new Application();
