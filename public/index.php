<?php

/**
 * A simple, clean and secure PHP Login Script embedded into a small framework.
 * Also available in other versions: one-file, minimal, advanced. See php-login.net for more info.
 *
 * MVC FRAMEWORK VERSION 3.0 (in development)
 *
 * @author Panique
 * @link http://www.php-login.net/
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// TODO rename ROOT and APP to something with PATH_

// set a constant that holds the project's folder path, like "/var/www/".
// DIRECTORY_SEPARATOR adds a slash to the end of the path
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

// The Composer auto-loader (official way to load Composer contents) to load external stuff automatically
if (file_exists(ROOT . 'vendor/autoload.php')) {
    require ROOT . 'vendor/autoload.php';
}

// Load application config (error reporting, database credentials etc.)
require APP . '/config/config.php';

// The auto-loader to load the php-login related internal stuff automatically
require APP . '/config/autoload.php';

// Start our application
$app = new Application();
