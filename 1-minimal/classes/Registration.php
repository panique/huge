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
    *  @var  boolean
    */
    private $is_registration_ok = false;

    /**
    *  The constructor execute the registration on set the registration status
    */
    public function __construct()
    {
        parent::__construct();

        $action = filter_input(
            INPUT_GET,
            'action',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_SCALAR|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH
        );

        if ('register' == $action) {
            $this->is_registration_ok = $this->registerNewUser();
        }
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
        //1 - Input Filtering and Validation
        $arguments = array(
            'user_name' => array('filter' => FILTER_CALLBACK, 'options' => 'Auth::isValidUserName'),
            'user_email' => array('filter' => FILTER_CALLBACK, 'options' => 'Auth::isValidEmail'),
            'user_password_new' => array('filter' => FILTER_CALLBACK, 'options' => 'Auth::isValidPassword'),
            'user_password_repeat' => array('filter' => FILTER_CALLBACK, 'options' => 'Auth::isValidPassword'),
        );
        $params = filter_input_array(INPUT_POST, $arguments);
        $this->errors = array_map(array($this, 'isDataValid'), $params);
        foreach ($this->errors as $key => $value) {
            if ($value == self::DATA_OK) {
                unset($this->errors[$key]);
            }
        }

        if (! isset($this->errors['user_password_new'], $this->errors['user_password_repeat']) &&
            ($params['user_password_new'] != $params['user_password_repeat'])
        ) {
            $this->errors['user_password_repeat'] = self::DATA_MISMATCH;
        }

        if (! isset($this->errors['user_name'], $this->errors['user_email']) &&
            $this->isUserExists($params['user_name'], $params['user_email'])
        ) {
            $this->errors['user_name'] = self::USER_EXISTS;
        }

        if (count($this->errors)) {
            return false;
        }

        //2 - write new user data into database
        $params['user_password_hash'] = password_hash($params['user_password_new'], PASSWORD_DEFAULT);
        unset($params['user_password_new'], $params['user_password_repeat']);
        $params = array_map(array($this->conn, 'real_escape_string'), $params);
        $res = $this->conn->query(
            "INSERT INTO users (".implode(',', array_keys($params)).") VALUES ('".implode("','", $params)."')"
        );
        if (! $res) {
            $this->errors['user_name'] = self::REGISTRATION_FAILED;
            return false;
        }

        return true;
    }
}
