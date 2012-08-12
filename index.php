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


// class autoloader #1 for PHP 5.3+
// automatically loads all needed classes
spl_autoload_register( function($class) {
    include('classes/' . $class . '.class.php');
});

// class include #2 for PHP 5.2
// if you are using an older PHP version that 5.3, you can simply use the next line instead of the above autoloader
// include('classes/Login.class.php');


// start this baby
$login = new Login();

// base structure
if ($login->checkForRegisterPage()) {
        include("views/login/register.php");
} else {
    // are we logged in ?
    if ($login->isLoggedIn()) {
        include("views/login/logged_in.php");
        // further stuff here
    } else {
        // not logged in, showing the login form
        include("views/login/login_form.php");
    }
}

?>