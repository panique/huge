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
   /**
   * Database connection
   * @var MySQLi
   */
   private $conn;
   
   /**
    * Collection of error messages
    * @var array
    */
   private $errors = array();
   
   /**
    * Collection of regular expressions to validate user data
    * @var array
    */
   public static final $regexp = array(
      'user_name' => '^[a-zA-Z0-9]{2,64}$',
      'user_password' => '^.{6,}$'
   );

   /********************************************************
    * Possible Error using Constants to enable localization 
    ********************************************************/
   const DATA_MISSING = 1;  //data is missing
   const DATA_INVALID = 2;  //data is invalid
   const DATA_MISMATCH = 3; //string mismatch between 2 string
   const REGISTRATION_FAILED = 1; //registration failed (db error)
   const USER_EXISTS = 1; //user submitted already exists in database
   const USER_UNKNOWN = 2;  //user unknown (user name OR password Error)
   
   /**
    * Used to generated a unique token for each user
    * @var string
    */
   private $secretKey = 'This is my hidden secret key'; //you should change this phrase
   
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
    * Return the regular expressions (can be use to match PHP and HTML5 regular expression)
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
               'regexp' => '/'.self::$regexp['user_password'].'/'
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
               'regexp' => '/'.self::$regexp['user_name'].'/'
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
 
    /**
    * is a user already with the given login OR email exists in the database
    * @param str $login the user name
    * @param str $email the user email
    * 
    * @return boolean
    */
    private function isUserExists($login, $email)
    {
        $login = $this->conn->real_escape_string($login);
        $email = $this->conn->real_escape_string($email);
        $res = $this->conn->query(
            "SELECT COUNT(user_id) AS nb FROM users WHERE user_name = '$login' OR user_email = '$email'"
        );
        $nb = $res->fetch_assoc();
        return (bool) $nb['nb'];
    }
    
    /**
     * generate a unique token 
     * @param  string $login a string to generate the token with
     * @return string        the generated token
     */
    private function generateToken($login)
    {
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $timestamp = time();
        $secret = sha1($login.'|'.$this->secretKey.'|'.$userAgent.'|'.$timestamp);
        return $login.'|'.$timestamp.'|'.$secret;
    }

    /**
     * validate a token against itself and against time 
     * which makes session timeout possible
     * @param  string $str the token to be validated
     * @return boolean
     */
    private function isValidateToken($str)
    {
        list($login, $timestamp, $secret) = explode('|', $str);
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (
            sha1($login.'|'.$this->secretKey.'|'.$userAgent.'|'.$timestamp) != $secret ||
            strtotime('NOW - 30 MINUTES') > $timestamp
        ) {
            return false;
        }
        return true;
    }
    
}
