<?php

/**
* Index
*
* This is the base index file
*
* @package SimplePHPLogin
* @author Christian Lavie
* @license GNU General Public License Version 3
********************************************************************************/

// class autoloader
spl_autoload_register(function($class) {
    include 'classes/' . $class . '.class.php';
});

// login
$login = new Login();

// base structure
if (isset($_GET["action"]) && $_GET["action"]=="register") {
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