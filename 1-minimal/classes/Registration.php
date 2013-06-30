<?php

/**
* class Registration
* handles the user registration
* 
* @author Panique <panique@web.de>
* @version 1.0
*/
class Registration 
{
    private $conn; // database connection   
    private $is_registration_ok = false;
    private $errors = array();  // collection of error messages

    const DATA_MISSING = 1;
    const DATA_INVALID = 2;
    const DATA_MISMATCH = 3;
    const REGISTRATION_FAILED = 1;
    const USER_EXISTS = 1;
 
    public function __construct() 
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_errno || ! $this->conn->set_charset(DB_CHARSET)) {
            die("Sorry, no database connection.");
        }

        $this->is_registration_ok = $this->registerNewUser();
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

    public function isRegistrationSuccessful()
    {
        return $this->is_registration_ok;
    }

    /**
    * registerNewUser
    * 
    * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
    * everything is fine
    */
    private function registerNewUser() 
    {
        $this->errors = array();
        $this->messages = array();
        if (filter_has_var(INPUT_POST, 'register')) {
            return false;
        }

        //1 - Form filtering
        $arguments = array(
            'user_name' => array('filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/^[a-z0-9]{2,64}$/i')),
            'user_email' => FILTER_VALIDATE_EMAIL,
            'user_password_new' => array('filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/^.{6,}$/')),
            'user_password_repeat' => array('filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/^.{6,}$/')),
        );
        $params = filter_input_array(INPUT_POST, $arguments);
        if (! $params) {
            $this->errors['submission'] = self::DATA_MISSING;
            return false;
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

        //2 - data prepared and sanitized for db inclusion
        $params['user_password'] = password_hash($params['user_password_new'], PASSWORD_DEFAULT);
        unset($params['user_password_new'], $params['user_password_repeat']);
        $params = array_map(array($this->conn, 'real_escape_string'), $params);

        //3 - check if user already in the table
        $res = $this->conn->query(
            "SELECT * FROM users WHERE user_name = '{$params['user_name']}' OR user_email = '{$params['user_email']}'"
        );

        if ($res->num_rows > 0) {
            $this->errors['uniqueness'] = self::USER_EXISTS
            return false;
        }

        //4 - write new users data into database
        $res = $this->conn->query(
            "INSERT INTO users (".implode(',', array_keys($params)).") VALUES ('."implode("','", $params)".')"
        );

        if (! $res) {
            $this->errors['registration'] = self::REGISTRATION_FAILED;
            return false;
        }

        return true;
    }
}
