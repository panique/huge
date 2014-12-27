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

// Auto-loading the classes (currently only from application/libs) via Composer's PSR-4 autoloader
// Later it might be useful to use a namespace here, but for now let's keep it as simple as possible
require '../vendor/autoload.php';

// Start our application
new Application();
