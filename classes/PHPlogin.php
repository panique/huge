<?php
//=========================================================

// This file is designed to be include in every page of your site before header is send. 
/* EXAMPLE:
 
      // if not defined, phplogin assume current directory.
      define('PHPLOGIN_LOCATION', 'path/to/my_foobar_project/PHP-login/');	

      // if PHPLOGIN_LOCATION have not be defined replace it by your path or remove it.  
      include_once PHPLOGIN_LOCATION.'include/php-login.php'; 

 	  // The security checks and the login verrification are done here by the constructor.
 	  $PHPlogin = new PHPlogin();
*/	


// The method display() is designed to be call inside the <body> but before sensitive data.  
/* EXAMPLE:
	  
      //this function print the html content based on the user status and request.
  	  $PHPlogin->display();
*/	

//=========================================================
// Public Outlines:
//  $PHPlogin->add_message($message)
//  $PHPlogin->add_error($error)

//=========================================================




// PHPLOGIN_LOCATION is not defined. script use the current diroctory.
!defined('PHPLOGIN_LOCATION') ? define('PHPLOGIN_LOCATION', ''): TRUE ;

require_once(PHPLOGIN_LOCATION.'config/config.php');
require_once(PHPLOGIN_LOCATION.'classes/Database.class.php');
require_once(PHPLOGIN_LOCATION.'classes/Login.class.php');
require_once(PHPLOGIN_LOCATION.'classes/Nonce.class.php');

class PHPlogin {
	private   $errors    = array();
	private   $messages  = array();
	protected $db      ;
	private   $login   ;
	public    $nonce   ;


	public function __construct(){			
		$this->db    = new Database();
		$this->nonce = new Nonce($this->db); // nonces protect aganst replay attacks and semantic URL attacks
		$this->login = new Login($this->db, $this->nonce); 
	}


	public function display(){
		if ($this->login->displayRegisterPage()) {
			include(PHPLOGIN_LOCATION.'views/header/PHP-login-style.php');
		    include(PHPLOGIN_LOCATION.'views/login/register.php');
			$this->display_messages();
		 	echo "</body></html>".PHP_EOL;
		 	exit(0);
			 
		} elseif ($this->login->isUserLoggedIn() === TRUE) {
			include(PHPLOGIN_LOCATION.'views/header/PHP-login-style.php');
			include(PHPLOGIN_LOCATION.'views/login/logged_in.php');
			
		} else {
			include(PHPLOGIN_LOCATION.'views/header/PHP-login-style.php');
			include(PHPLOGIN_LOCATION.'views/login/not_logged_in.php');
			$this->display_messages();
		 	echo "</body></html>".PHP_EOL;
			exit(0);
		}
	}


	public function add_error($error){
		$this->errors[] = $error;
	}


	public function add_message($message){
		$this->messages[] = $message;
	}


	private function display_messages(){
		// collect  everyone's messages and errors
		$errors = array_merge($this->login->errors, $this->nonce->errors, $this->errors);
	 	$messages = array_merge($this->login->messages, $this->messages);
	 
		//display errors
        foreach ($errors as $error) {
	    	echo '<div class="login_message error">'.PHP_EOL;
	        echo $error.PHP_EOL; 
	    	echo '</div>'.PHP_EOL;            
	    }
	
			//display messages
		foreach ($messages as $message) {
	    	echo '<div class="login_message success">'.PHP_EOL;
	        echo $message.PHP_EOL; 
	   		echo '</div>'.PHP_EOL;              
	    }    
		
		//clean buffers	
      	$this->login->errors    = array();
      	$this->login->messages  = array();
      	$this->nonce->errors    = array();
      	$this->messages         = array();
      	$this->errors           = array();	    
	}





    function __destruct(){
    	$this->display_messages();
	}

}