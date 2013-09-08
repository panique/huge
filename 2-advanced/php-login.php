<?php

/**
 * Check PHP prerequisites and load common variables, libraries, classes 
 * and functions necessary for php-login script to work properly.
 */
 
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once('libraries/password_compatibility_library.php');
}

// include the config
require_once('config/config.php');

// include the PHPMailer library
require_once('libraries/PHPMailer.php');

// detection of the language for the current user
$user_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
// if translation file for the specified language doesn't exist, we use default english file
if (! file_exists('lang/' . $user_lang . '.php')) {
    $user_lang = 'en';
}
// save language as constant and include language translated strings
define('PHPLOGIN_LANG', $user_lang);
include('lang/' . PHPLOGIN_LANG . '.php');

// load the login and registration classes
require_once('classes/Login.php');
require_once('classes/Registration.php');
