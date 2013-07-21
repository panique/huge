<?php

/**
* class Login
* handles the user login/logout/session
*
* @author Panique <panique@web.de>
* @version 1.2
*/
class Login
{
    /**
     * the User status
     * @var boolean
     */
    private $is_logged_in = false; // status of login
    
    /**
    * Auth Object
    * @var Auth
    */
    private $auth;

    /**
    * The constructor handles login/logout action
    */
    public function __construct()
    {
        $this->auth = new Auth();
        
        // create/read session
        $sessionId = session_id();
        if (empty($sessionId)) {
            session_start();
        }

        //the action to take
        $action = filter_input(
            INPUT_GET,
            'action',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_SCALAR|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH
        );

        if (isset($_SESSION['user_token'])) {
            $this->is_logged_in = $this->loginWithSessionData();
            if (! $this->is_logged_in || filter_has_var(INPUT_GET, 'logout')) {
                $this->doLogout();
            }
        } elseif ('login' == $action) {
            $this->is_logged_in = $this->loginWithPostData();
            if (! $this->is_logged_in) {
                $this->doLogout();
            }
        }

    }

    /**
    * perform the logout
    */
    public function doLogout()
    {
        $this->is_logged_in = false;
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
        if (! $this->auth->isValidToken($_SESSION['user_token'])) {
            $this->errors['user_token'] = Auth::DATA_INVALID;
            return false;
        }

        //2 - User Authentification
        $auth = explode('|', $_SESSION['user_token']);
        $login = filter_var($auth[0], FILTER_CALLBACK, array('options' => 'Auth::isValidUserName'));
        if (! $login) {
            $this->errors['user_name'] = Auth::DATA_INVALID;
            return false;
        } elseif (! ($user = $this->getUserByName($login))) {
            $this->errors['user_name'] = Auth::USER_UNKNOWN;
            return false;
        }

        //3 - Session Update
        $this->loadSession($user);
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
            'user_name' => array('filter' => FILTER_CALLBACK, 'options' => 'Auth::isValidUserName'),
            'user_password' => array('filter' => FILTER_CALLBACK, 'options' => 'Auth::isValidPassword'),
        );
        $params = filter_input_array(INPUT_POST, $arguments);
        $this->errors = array_map('Auth::isDataValid', $params);
        foreach ($this->errors as $key => $value) {
            if ($value == Auth::DATA_OK) {
                unset($this->errors[$key]);
            }
        }
        if (count($this->errors)) {
            return false;
        }

        //2 - User Authentification
        $user = $this->auth->getUserByName($params['user_name']);
        if (! $user) {
            $this->errors['user_name'] = Auth::USER_UNKNOWN;
            return false;
        } elseif (! password_verify($params['user_password'], $user['user_password_hash'])) {
            $this->errors['user_password'] = Auth::DATA_INVALID;
            return false;
        }

        //3 - Session Update
        $this->loadSession($user);
        return true;
    }

    /**
     * load User Data into the session
     * @return void
     */
    private function loadSession(array $user)
    {
        foreach ($user as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $_SESSION['user_token'] = $this->auth->generateToken($user['user_name']);
    }
}
