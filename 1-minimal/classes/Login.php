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
        if (empty(session_id())) {
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
    * @return boolean user's login status
    */
    public function isUserLoggedIn()
    {
        return $this->is_logged_in;
    }

    /**
    * Connect a user depending on his session data
    */  
    public function loginWithSessionData()
    {
        if (! $this->isValidateToken($_SESSION['user_token'])) {
            $this->errors['user_token'] = self::DATA_INVALID;
            return false;
        }

        list($login, ) = explode('|', $_SESSION['user_token');
        $login = filter_var($_SESSION['user_name'], FILTER_CALLBACK, array($this, 'isValidUserName'));
        if (! $login) {
            $this->errors['user_name'] = self::DATA_INVALID;
            return false;
        }

        $user = $this->getUserByName($login);
        if (! $user) {
            $this->errors['user_name'] = self::USER_UNKNOWN;
            return false;            
        }

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
    public function loginWithPostData()
    {
        $this->errors = array();
        $params = filter_input_array(
            INPUT_POST,
            array(
                'user_name' => array(
                    'filter' => FILTER_CALLBACK,
                    'options' => array($this, 'isValidUserName')
                ),
                'user_password' => array(
                    'filter' => FILTER_CALLBACK,
                    'options' => array($this, 'isValidPassword')
                ),
            )
        );

        if (! $params) {
            $params = array_fill_keys(array('user_name', 'user_password', null);
        }

        foreach (array('user_name', 'user_password') as $key) {
            if (! is_null($params[$key])) {
                $this->errors[$key] = self::DATA_MISSING;
            } else if (! $params[$key]) {
                $this->errors[$key] = self::DATA_INVALID;
            }
        }

        if (count($this->errors)) {
            return false;
        }

        $user = $this->getUserByName($params['user_name']);
        if (! $user) {
            $this->errors['user'] = self::USER_UNKNOWN;
            return false;            
        }

        if (! password_verify($param['user_password'], $user['user_password_hash'])) {
            $this->errors['password'] = self::DATA_INVALID;
            return false;
        }

        foreach ($user as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $_SESSION['user_token'] = $this->generateToken($user['user_name']);
        return true;
    }

    /**
     * return the user data 
     * @param  str $login the user name
     * @return array      the user info
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
     * generate a unique token 
     * @param  string $login a string to generate the token with
     * @return string        the generated token
     */
    private function generateToken($login)
    {
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $timestamp = time();
        $secret = sha1($login.'|'.self::SECRET_KEY.'|'.$userAgent.'|'.$timestamp);
        return $login.'|'.$timestamp.'|'.$secret;
    }

    /**
     * validate a token
     * @param  string $str the token to be validated
     * @return boolean
     */
    private function isValidateToken($str)
    {
        list($login, $timestamp, $secret) = explode('|', $str);
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (
            sha1($login.'|'.self::SECRET_KEY.'|'.$userAgent.'|'.$timestamp) != $secret ||
            strtotime('NOW - 30 MINUTES') > $timestamp
        ) {
            return false;
        }
        return true;
    }

}
