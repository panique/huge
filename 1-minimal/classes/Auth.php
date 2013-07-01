<?php

/**
* class Registration
* handles the user registration
*
* @author Panique <panique@web.de>
* @version 1.0
*/
class Auth
{
   private $conn; // database connection  
   private $errors = array();  // collection of error messages
   public static final $regexp = array(
      'user_name' => '^[a-zA-Z0-9]{2,64}$',
      'user_password' => '^.{6,}$'
   );

   const DATA_MISSING = 1;
   const DATA_INVALID = 2;
   const DATA_MISMATCH = 3;
   const REGISTRATION_FAILED = 1;
   const USER_EXISTS = 1;
   const USER_UNKNOWN = 2;
   
   /**
    * The Constructor initialize the db connection
    */
   public function __construct()
   {
       $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
       if ($this->conn->connect_errno || ! $this->conn->set_charset(DB_CHARSET)) {
           die("Sorry, no database connection.");
       }
   }

   /**
    * Return the errors
    * @param  string $name an specified regular expression
    * @return mixed
    */
   public function getRegexp($name = null)
   {
       if (is_null($name)) {
           return self::$regexp;
       }
       if (isset(self::$regexp[$name])) {
           return self::$regexp[$name];
       }
       return null;
   }

   /**
    * Return the errors
    * @param  string $name an specified error
    * @return mixed
    */
   public function getErrors($name = null)
   {
       if (is_null($name)) {
           return $this->errors;
       }
       if (isset($this->errors[$name])) {
           return $this->errors[$name];
       }
       return null;
   }

   /**
    * check to see if the email is valid
    * @param  string  $str  the email to test
    * @return mixed  return the status to work with filter_* function 
    */
   public static function isValidEmail($str = null)
   {
      if (is_null($str)) {
         return null;
      }
      $str = filter_var($str, FILTER_VALIDATE_EMAIL);
      if (! $str || 64 > strlen($str)) {
         return false;
      }
      
      return $str;
   }

   /**
    * check to see if the password is valid
    * @param  string  $str the password to test
    * @return mixed        the status to work with filter_* function 
    */
   public static function isValidPassword($str = null)
   {
      if (is_null($str)) {
         return null;
      }
      $str = filter_var(
         $str,
         FILTER_VALIDATE_REGEXP,
         array(
            'options' => array(
               'regexp' => '/'.self::regexp['user_password'].'/'
            )
         )
      );
      if (! $str) {
         return false;
      }
      
      return $str;
   }

   /**
    * check to see if the username is valid
    * @param  string  $str the username to test
    * @return mixed        the status to work with filter_* function 
    */
   public static function isValidUserName($str = null)
   {
      if (is_null($str)) {
         return null;
      }
      $str = filter_var(
         $str,
         FILTER_VALIDATE_REGEXP,
         array(
            'options' => array(
               'regexp' => '/'.self::regexp['user_name'].'/'
            )
         )
      );
      if (! $str) {
         return false;
      }
      
      return $str;
   }

    /**
    * return the user data
    * @param str $login the user name
    * 
    * @return array the user info
    */
    private function getUserByName($login)
    {
        $login = $this->conn->real_escape_string($login);
        $res = $this->conn->query("SELECT * FROM users WHERE user_name = '$login'");
        if ($res->num_rows != 1) {
            return array();
        }
        return $res->fetch_assoc();
    }
}
