<?php

/**
 * Simple Sexy PHP Login Script
 * 
 * A simple PHP Login Script without all the nerd bullshit.
 * Uses PHP SESSIONS, modern SHA256-password-hashing and salting
 * and gives the basic functions a proper login system needs.
 * 
 * @package SimplePHPLogin
 * @author Panique <panique@web.de>
 * @link https://github.com/Panique/PHP-Login/
 * @license GNU General Public License Version 3 
 */

/**
 * Additional notes for experienced PHP guys/girls:
 * This script got a big code makeover in September 2012 to make this script more professional
 * and extendable. The database connection is not created within the login class anymore, now we have own
 * classes for both things: database connection and login process. So you can easily write your
 * own classes while using the main db connection.
 * From now, we create a database connection and pass it to each new object we create
 * (Dependency Injection Pattern, by the way). This might look stupid, but it's really good stuff.
 */

/**
 * include the configs / constants for the database connection
 */
require_once("config/db.php");

// class autoloader function, this includes all the classes that are needed by the script
// you can remove this stuff if you want to include your files manually
function autoload($class)
{
    require('classes/' . $class . '.class.php');
}

// automatically loads all needed classes, when they are needed
spl_autoload_register("autoload");


//create a database connection
$db    = new Database();

// start this baby and give it the database connection
$login = new Login($db);

// base structure
if ($login->displayRegisterPage()) {
        include("views/login/register.php");
} else {
    // are we logged in ?
    if ($login->isUserLoggedIn()) {
        include("views/login/logged_in.php");
        // further stuff here
    } else {
        // not logged in, showing the login form
        include("views/login/not_logged_in.php");
    }
}
