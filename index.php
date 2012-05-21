<?php

/**
 * SCRIPT login
 * 
 * simple, but very effective, clean and secure login script
 * pro: oop, clean, cool, documented
 * pro: works projectwide in every subfolder, just create new Login() and check for $login->isLoggedIn()
 * pro: works with $_SESSION, which means minimal database load
 * con: works with $_SESSION, which is problematic in cloud apps (as clouds share one database, but not one filesystem)
 * 
 * @author Christian Lavie <panique@web.de>
 * @version 1.0
 * 
 */

// autoloader (classes)
//function __autoload($class) {
//    include 'classes/' . $class . '.class.php';
//}

spl_autoload_register(function($class) {
    include 'classes/' . $class . '.class.php';
});


// login
$login = new Login();

if ($login->isLoggedIn()) {
    include("views/login/logged_in.php");
} else {
    include("views/login/login_form.php");
}

    /*
    if (isset($_GET["action"]) && $_GET["action"]=="register") {
        include("views/login/register.php");
    } else {
        include("views/login/login_form.php");
    }
    */

?>