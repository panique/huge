<?php

/**
* class Login
* handles the user login/logout/session
*
* @author Panique <panique@web.de>
* @version 1.2
*/
class Login extends Auth
{
   private $is_logged_in = false; // status of login
   private $is_logged_out = false;

   /**
    * the function "__construct()" automatically starts whenever an object of this class is created,
    * you know, when you do "$login = new Login();"
    */    
   public function __construct() {
       
       parent::__construct();

       // create/read session
       if (empty(session_id())) {
           session_start();
       }                          

       $this->is_logged_out = false;
       $this->is_logged_in = false;
       if (isset($_GET["logout"])) {
           $this->is_logged_out = $this->doLogout();
           if ($this->is_logged_out) {
               $this->is_logged_in = false;
           }
           return;
       }

       // if user has an active session on the server
       if (isset($_SESSION['user_name'], $_SESSION['user_logged_in']) && 1 == $_SESSION['user_logged_in']) {
           $this->is_logged_in = $this->loginWithSessionData();                
           return;

       }

       // if user just submitted a login form
       if (isset($_POST["login"])) {
           $this->is_logged_in = $this->loginWithPostData();
       }        
   }    
   
   private function loginWithSessionData()
   {
       // set logged in status to true, because we just checked for this:
       // !empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)
       // when we called this method (in the constructor)
       $login = filter_var($_SESSION['user_name'], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-z0-9]{2,64}$/i')));
       if (! $login) {
            $this->errors['session'] = self::DATA_INVALID;
            return false;
       }

       //2 - DB Connection
       $res = $this->conn->query(
           "SELECT * FROM users WHERE user_name = '".$this->conn->real_escape_string($login)."'"
       );
       if ($res->num_rows != 1) {
           $this->errors['user'] = self::USER_UNKNOWN;
           return false;
       }

       $user = $res->fetch_assoc();
       foreach ($user as $key => $value) {
           $_SESSION[$key] = $value;
       }
       $_SESSION['user_logged_in'] = 1;
       return true;  
   }
   
   private function loginWithPostData()
   {
       if (! filter_has_var(INPUT_POST, 'user_name') || ! filter_has_var(INPUT_POST, 'user_password') ) {
            $this->errors['submission'] = self::DATA_MISSING;
            return false;
       }

       $params = filter_input_array(
           INPUT_POST,
           array(
               'user_name' => array('filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/^[a-z0-9]{2,64}$/i')),
               'user_password' => array('filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/^.{6,}$/')),
           )
       )

       if (! isset($params['user_name'], $params['user_password'])) {
            $this->errors['validation'] = self::DATA_INVALID;
            return false;
       }

       $params['user_name'] = $this->conn->real_escape_string($params['user_name']);
       $res = $this->conn->query("SELECT * FROM users WHERE user_name = '{$params['user_name']}'");
       if ($res->num_rows != 1) {
           $this->errors['user'] = self::USER_UNKNOWN;
           return false;
       }

       $user = $res->fetch_assoc();
       if (! password_verify($param['user_password'], $user['user_password_hash'])) {
            $this->errors['password'] = self::DATA_INVALID;
            return false;
       }

       foreach ($user as $key => $value) {
           $_SESSION[$key] = $value;
       }
       $_SESSION['user_logged_in'] = 1;
       return true;      
   }
   
   /**
    * perform the logout
    */
   public function doLogout()
   {
       $_SESSION = array();
       session_destroy();  
       return true;
   }
   
   /**
    * simply return the current state of the user's login
    * @return boolean user's login status
    */
   public function isUserLoggedIn()
   {
       return $this->is_logged_in;
   }

   public function isUserLogOut()
   {
       return $this->is_logged_out;
   }

}
