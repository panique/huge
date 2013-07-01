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
    /**
     * the User status
     * @var boolean
     */
    private $is_logged_in = false; // status of login

    /**
    * The constructor handles login/logout action
    */
    public function __construct()
    {
        parent::__construct();
        
        // create/read session
        $sessionId = session_id();
        if (empty($sessionId)) {
            session_start();
        }

        $this->is_logged_in = false;
        if (isset($_SESSION['session_token'])) {
            $this->is_logged_in = $this->loginWithSessionData();
        } elseif (filter_has_var(INPUT_POST, 'login')) {
            $this->is_logged_in = $this->loginWithPostData();
        }

        if (! $this->is_logged_in || filter_has_var(INPUT_GET, 'logout')) {
            $this->doLogout();
        }
    }

    /**
    * perform the logout
    */
    public function doLogout()
    {
        $_SESSION = array();
        session_destroy();
    }

    /**
    * simply return the current state of the user's login
    * 
    * @return boolean user's login status
    */
    public function isUserLoggedIn()
    {
        return $this->is_logged_in;
    }

    /**
    * Connect a user depending on his session data
    */
    private function loginWithSessionData()
    {
        $this->errors = array();
        //1 - Input Filtering and Validation
        if (! $this->isValidateToken($_SESSION['user_token'])) {
            $this->errors['user_token'] = self::DATA_INVALID;
            return false;
        }

        //2 - User Authentification
        $auth = explode('|', $_SESSION['user_token']);
        $login = filter_var($auth[0], FILTER_CALLBACK, array('options' => 'Auth::isValidUserName'));
        if (! $login) {
            $this->errors['user_name'] = self::DATA_INVALID;
            return false;
        }

        $user = $this->getUserByName($login);
        if (! $user) {
            $this->errors['user_name'] = self::USER_UNKNOWN;
            return false;
        }

        //3 - Session Update
        foreach ($user as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $_SESSION['user_token'] = $this->generateToken($user['user_name']);
        return true;
    }

    /**
     * Connect a user depending on his submitted post data
     * 
     * @return boolean
     */
    private function loginWithPostData()
    {

        $this->errors = array();
        //1 - Input Filtering and Validation
        $arguments = array(
            'user_name' => array('filter' => FILTER_CALLBACK, 'options' => array('Auth::isValidUserName')),
            'user_password' => array('filter' => FILTER_CALLBACK, 'options' => array('Auth::isValidPassword')),
        );
        $params = filter_input_array(INPUT_POST, $arguments);
        foreach (array_keys($arguments) as $key) {
            if (! is_null($params[$key])) {
                $this->errors[$key] = self::DATA_MISSING;
            } else if (! $params[$key]) {
                $this->errors[$key] = self::DATA_INVALID;
            }
        }
        if (count($this->errors)) {
            return false;
        }
        //2 - User Authentification
        $user = $this->getUserByName($params['user_name']);
        if (! $user) {
            $this->errors['user'] = self::USER_UNKNOWN;
            return false;
        }

        if (! password_verify($params['user_password'], $user['user_password_hash'])) {
            $this->errors['password'] = self::DATA_INVALID;
            return false;
        }
        //3 - Session Update
        foreach ($user as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $_SESSION['user_token'] = $this->generateToken($user['user_name']);
        return true;
    }
}
