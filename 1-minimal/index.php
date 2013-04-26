<?php

/**
 * A simple, clean and secure PHP Login Script
 * 
 * MINIMAL VERSION
 * (check the website / github / facebook for other versions)
 * 
 * A simple PHP Login Script without all the nerd bullshit.
 * Uses PHP SESSIONS, modern SHA256-password-hashing and salting
 * and gives the basic functions a proper login system needs.
 * 
 * Please remember: this is just the minimal version of the login script, so if you need a more
 * professional version, have a look on the github repo. buzzwords: MVC, dependency injected,
 * one shared database connection, PDO, prepared statements, PSR-0/1/2 and documented in phpDocumentor style
 * 
 * @package php-login
 * @author Panique <panique@web.de>
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// include the configs / constants for the database connection
require_once("config/db.php");

// load the login class
require_once("classes/Login.php");

// create a login object. when this object is created, it will do all login/logout stuff automaticly
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    include("views/logged_in.php");
    
} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("views/not_logged_in.php");
}