<?php

/**
 * A simple, clean and secure PHP Login Script
 * 
 * MVC FRAMEWORK VERSION
 * Check Github for other versions
 * Check develop branch on Github for bleeding edge versions
 * 
 * A simple PHP Login Script embedded into a small framework.
 * Uses PHP sessions, the most modern password-hashing and salting
 * and gives all major functions a proper login system needs.
 * 
 * @package php-login
 * @author Panique <panique@web.de>
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// dev error reporting
error_reporting(E_ALL);

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<') ) {
  exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");  
}

// loading config
require 'config/config.php';

require 'libs/PasswordCompatibilityLibrary.php';

// the autoloading function, which will be called every time a file "is missing"
// NOTE: don't get confused, this is not "__autoload", the now deprecated function
// The PHP Framework Interoperability Group (@see https://github.com/php-fig/fig-standards) recommends using a
// standardized autoloader https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md, so we do:
function autoload($class) {

    require "libs/" . $class . ".php";
}

// spl_autoload_register defines the function that is called every time a file is missing. as we created this
// function above, every time a file is needed, autoload(THENEEDEDCLASS) is called
spl_autoload_register("autoload");

// start our app
$app = new Bootstrap();
