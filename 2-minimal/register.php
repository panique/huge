<?php

/**
 * A simple, clean and secure PHP Login Script
 *
 * MINIMAL VERSION
 * (check the website / github / facebook for other versions)
 *
 * A simple PHP Login Script.
 * Uses PHP SESSIONS, modern password-hashing and salting
 * and gives the basic functions a proper login system needs.
 *
 * Please remember: this is just the minimal version of the login script, so if you need a more
 * advanced version, have a look on the github repo. there are / will be better versions, including
 * more functions and/or much more complex code / file structure. buzzwords: MVC, dependency injected,
 * one shared database connection, PDO, prepared statements, PSR-0/1/2 and documented in phpDocumentor style
 *
 * @package php-login
 * @author Panique
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}

// include the configs / constants for the database connection
require_once("config/db.php");

// load the registration class
require_once("classes/Registration.php");

// create the registration object. when this object is created, it will do all registration stuff automaticly
// so this single line handles the entire registration process.
$registration = new Registration();

// showing the register view (with the registration form, and messages/errors)
include("views/register.php");
