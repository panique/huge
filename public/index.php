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

// Auto-loading the classes (from application/libs btw)
if (file_exists('../vendor/autoload.php')) {
    // if Composer is used, then use Composer's auto-loader
    require '../vendor/autoload.php';
} else {
    // if not, then use the custom auto-loader
    require '../application/config/autoload.php';
}

// Load application config (error reporting, database credentials etc.)
require '../application/config/config.php';

// Start our application
$app = new Application();
