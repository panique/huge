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
   private $regexp = array(
      'user_name' => '^[a-zA-Z0-9]{2,64}$',
      'user_password' => '^.{6,}$'
   );

   const DATA_MISSING = 1;
   const DATA_INVALID = 2;
   const DATA_MISMATCH = 3;
   const REGISTRATION_FAILED = 1;
   const USER_EXISTS = 1;
   const USER_UNKNOWN = 2;
   
   public function __construct()
   {
       $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
       if ($this->conn->connect_errno || ! $this->conn->set_charset(DB_CHARSET)) {
           die("Sorry, no database connection.");
       }
   }

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

   protected function isValidEmail($str = null)
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

   protected function isValidPassword($str = null)
   {
      if (is_null($str)) {
         return null;
      }
      $str = filter_var(
         $str,
         FILTER_VALIDATE_REGEXP,
         array(
            'options' => array(
               'regexp' => '/'.$this->regexp['user_password'].'/'
            )
         )
      );
      if (! $str) {
         return false;
      }
      
      return $str;
   }

   protected function isValidUserName($str = null)
   {
      if (is_null($str)) {
         return null;
      }
      $str = filter_var(
         $str,
         FILTER_VALIDATE_REGEXP,
         array(
            'options' => array(
               'regexp' => '/'.$this->regexp['user_name'].'/'
            )
         )
      );
      if (! $str) {
         return false;
      }
      
      return $str;
      
   }

}
