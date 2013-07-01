<?php

/**
* class Registration
* handles the user registration
*
* @author Panique <panique@web.de>
* @version 1.0
*/
class Registration extends Auth
{
   /**
    *  Registration Status
    * 
    */
   private $is_registration_ok = false;

   /**
    *  The constructor execute the registration on set the registration status
    *  
    */
   public function __construct()
   {
       parent::__construct();
       $this->is_registration_ok = $this->registerNewUser();
   }

   /**
    *  return the registration status
    * 
    *  @return boolean
    */
   public function isRegistrationSuccessful()
   {
       return $this->is_registration_ok;
   }

   /**
   * registerNewUser
   *
   * handles the entire registration process. 
   * checks all error possibilities, 
   * and creates a new user in the database if
   * everything is fine
   * 
   * @return boolean
   * 
   */
   private function registerNewUser()
   {
       //1 - reset the errors property
       $this->errors = array();
       if (filter_has_var(INPUT_POST, 'register')) {
           return false;
       }

       //2 - Input Filtering and Validation
       $arguments = array(
           'user_name' => array('filter' => FILTER_CALLBACK, 'options' => array('Auth::isValidUserName')),
           'user_email' => array('filter' => FILTER_CALLBACK, 'options' => array('Auth::isValidEmail')),
           'user_password_new' => array('filter' => FILTER_CALLBACK, 'options' => array('Auth::isValidPassword')),
           'user_password_repeat' => array('filter' => FILTER_CALLBACK, 'options' => array('Auth::isValidPassword')),
       );
       $params = filter_input_array(INPUT_POST, $arguments);
       if (! $params) {
            $params = array_fill_keys(array_keys($arguments), null);
       }
       foreach (array_keys($arguments) as $keys) {
           $value = $params[$keys];
           if (is_null($value)) {
               $this->errors[$keys] = self::DATA_MISSING;
           } elseif (! $value) {
               $this->errors[$keys] = self::DATA_INVALID;
           }
       }
       if (empty($this->errors) && ($params['user_password_new'] != $params['user_password_repeat'])) {
           $this->errors['user_password'] = self::DATA_MISMATCH;
       }
       if (count($this->errors)) {
           return false;
       }

       //3 - data prepared and sanitized for db inclusion
       $params['user_password'] = password_hash($params['user_password_new'], PASSWORD_DEFAULT);
       unset($params['user_password_new'], $params['user_password_repeat']);
       $params = array_map(array($this->conn, 'real_escape_string'), $params);

       //4 - check if user already in the table
       $res = $this->conn->query(
           "SELECT * FROM users WHERE user_name = '{$params['user_name']}' OR user_email = '{$params['user_email']}'"
       );

       if ($res->num_rows > 0) {
           $this->errors['user_name'] = self::USER_EXISTS;
           return false;
       }

       //5 - write new user data into database
       $res = $this->conn->query(
           "INSERT INTO users (".implode(',', array_keys($params)).") VALUES ('."implode("','", $params)".')"
       );

       if (! $res) {
           $this->errors['user_name'] = self::REGISTRATION_FAILED;
           return false;
       }

       return true;
   }
}
