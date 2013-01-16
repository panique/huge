<?php
require_once("/path/to/PHP-login/config/db.php");

	function autoload($class){
	    require('/path/to/PHP-login/classes/' . $class . '.class.php');
	}
	
	// automatically loads all needed classes, when they are needed
	spl_autoload_register("autoload");
	//create a database connection
	$db    = new Database();
	// start this baby and give it the database connection
	$login = new Login($db, FALSE);

	
	//Loggin check
	if ($login->isUserLoggedIn()) {
		echo "you are logged.";
	}else{
		echo "you are not logged.";
	}
?>
