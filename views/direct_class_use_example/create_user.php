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
	$login = new Login($db, TRUE);

?>
 
 
 
 
 
 
 
 <form method="post" action="create_user.php">  
	username: <input type="text" name="user_name" value="Username" /><br>
        email: <input type="text" name="user_email" value="eMail" /><br>
        password:<input type="password" name="user_password_new" autocomplete="off" /><br>
        retype password:<input type="password" name="user_password_repeat" autocomplete="off" /><br>
        <input type="submit"  name="register" value="Create" /> <br>            
 </form>