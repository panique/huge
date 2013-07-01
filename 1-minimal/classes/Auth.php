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

}
