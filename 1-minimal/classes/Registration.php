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
    /**
    *  Registration Status
    *  @var  boolean
    */
    private $is_registration_ok = false;
    
    /**
    *  Auth Object
    *  @var Auth
    */    
    private $auth;

    /**
    *  The constructor execute the registration and set the registration status
    */
    public function __construct()
    {
        $this->auth = new Auth();

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
        $this->errors = array_map('Auth::isDataValid', $params);
        foreach ($this->errors as $key => $value) {
            if ($value == Auth::DATA_OK) {
                unset($this->errors[$key]);
            }
        }

        if (! isset($this->errors['user_password_new'], $this->errors['user_password_repeat']) &&
            ($params['user_password_new'] != $params['user_password_repeat'])
        ) {
            $this->errors['user_password_repeat'] = Auth::DATA_MISMATCH;
        }

        if (! isset($this->errors['user_name'], $this->errors['user_email']) &&
            $this->auth->isUserExists($params['user_name'], $params['user_email'])
        ) {
            $this->errors['user_name'] = Auth::USER_EXISTS;
        }

        if (count($this->errors)) {
            return false;
        }

        //2 - write new user data into database
        $params['user_password'] = $params['user_password_new'];
        unset($params['user_password_new'], $params['user_password_repeat']);
        if (! $this->auth->addUser($params)) {
            $this->errors['user_name'] = Auth::REGISTRATION_FAILED;
            return false;
        }

        return true;
    }
}
