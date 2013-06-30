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
    private $db_connection; // database connection   
    private $is_registration_ok = false;
    private $errors = array();  // collection of error messages
    private $errorMessages = array(
        'user_name' => array(
            'missing' => 'Username is missing',
            'invalid' =>'Username does not fit the name sheme: only a-Z and numbers are allowed, 2 to 64 characters',
        ),
        'user_email' => array(
            'missing' => 'Email missing',
            'invalid' =>'Invalid Email',
        ),
        'user_password_new' => array(
            'missing' => 'Password missing',
            'invalid' =>'Password has a minimum length of 6 characters',
        ),
        'user_password_repeat' => array(
            'missing' => 'Password missing',
            'invalid' =>'Password has a minimum length of 6 characters',
        ),
    );
 
    public function __construct() 
    {
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
        if (! filter_has_var(INPUT_POST, 'register')) {
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
            $this->errors['submission'] = 'Empty Submission';
            return false;
        }

        foreach (array_keys($arguments) as $keys) {
            $value = $params[$keys];
            if (is_null($value)) {
                $this->errors[$keys] = $this->errorMessages[$keys]['missing'];
            } elseif (! $value) {
                $this->errors[$keys] = $this->errorMessages[$keys]['invalid'];
            }
        }
        if (empty($this->errors) && ($params['user_password_new'] != $params['user_password_repeat'])) {
            $this->errors['user_password'] = "Password and password repeat are not the same";
        }
        if (count($this->errors)) {
            return false;
        }

        //2 - DB Connection
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db_connection->connect_errno || ! $this->db_connection->set_charset(DB_CHARSET)) {
            $this->errors['connection'] = "Sorry, no database connection.";
            return false;
        }


        //data prepare and sanitaze for db inclusion
        $params['user_password'] = password_hash($params['user_password_new'], PASSWORD_DEFAULT);
        unset($params['user_password_new'], $params['user_password_repeat']);
        $params = array_map(array($this->db_connection, 'real_escape_string'), $params);

        //3-  check if user already exists
        $res = $this->db_connection->query(
            "SELECT * FROM users WHERE user_name = '{$params['user_name']}' OR user_email = '{$params['user_email']}'"
        );

        if ($res->num_rows > 0) {
            $this->errors['uniqueness'] = "Sorry, the user name OR the email is already taken.<br/>Please choose another one.";
            return false;
        }

        // write new users data into database
        $res = $this->db_connection->query(
            "INSERT INTO users (".implode(',', array_keys($params)).") VALUES ('."implode("','", $params)".')"
        );

        if (! $res) {
            $this->errors['registration'] = "Sorry, your registration failed. Please go back and try again.";
            return false;
        }

        return true;
    }
}
