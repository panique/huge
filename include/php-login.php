<?php
//This file is designed to be include in every page of your site before header is send.

if (! defined('PHPLOGIN_LOCATION')){
	define('PHPLOGIN_LOCATION', '');
	
}

//load configs. 
require_once(PHPLOGIN_LOCATION.'config/config.php');

// class inclusion can also be made manually.
function autoload($class){
    require(PHPLOGIN_LOCATION.'classes/' . $class . '.class.php');
}

// automatically loads all needed classes, when they are needed
spl_autoload_register("autoload");

//create a database connection
$db    = new Database();

// start this baby and give it the database connection
$login = new Login($db);


function phpLogin(){
	global $login;	
	if ($login->displayRegisterPage()) {
		 include(PHPLOGIN_LOCATION.'views/header/PHP-login-style.php');
	     include(PHPLOGIN_LOCATION.'views/login/register.php');
	 	 echo "</body></html>".PHP_EOL;
		exit(0);
		
	} elseif (! $login->isUserLoggedIn()) {
		 include(PHPLOGIN_LOCATION.'views/header/PHP-login-style.php');
		 include(PHPLOGIN_LOCATION.'views/login/not_logged_in.php');
	 	 echo "</body></html>".PHP_EOL;
		 exit(0);
	}
}


