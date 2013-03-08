<?php

// This file is designed to be include in every page of your site before header is send.
// The function phpLogin() is designed to be call after '<body>' and BEFORE sensitive data. 

if (! defined('PHPLOGIN_LOCATION')){
	define('PHPLOGIN_LOCATION', '');	
}

//load configs. 
require_once(PHPLOGIN_LOCATION.'config/config.php');

// class inclusion. 
require(PHPLOGIN_LOCATION.'classes/Database.class.php');
require(PHPLOGIN_LOCATION.'classes/Login.class.php');
require_once(PHPLOGIN_LOCATION.'classes/Nonce.class.php');

//create a database connection
$db    = new Database();

// start the nonce tools with database connection
// nonces protect aganst replay attacks and semantic URL attacks
$nonce = new Nonce($db);

// start this baby and give it the database connection
$login = new Login($db);

function phpLogin(){
	global $login;	
	global $nonce;	
	
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
	} elseif ($login->isUserLoggedIn() === TRUE) {
		include(PHPLOGIN_LOCATION.'views/header/PHP-login-style.php');
		include(PHPLOGIN_LOCATION.'views/login/logged_in.php');
	}
}


