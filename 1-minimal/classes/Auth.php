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
    /**
    * Database connection
    * @var MySQLi
    */
    protected $conn;

    /**
    * Collection of error messages
    * @var array
    */
    protected $errors = array();

    /**
    * Collection of regular expressions to validate user data
    * @var array
    */
    public static $regexp = array(
        'user_name' => '^[a-zA-Z0-9]{2,64}$',
        'user_password' => '^.{6,}$'
    );

    /**
    * action to be taken
    * @var string
    */
    protected $action;

    /********************************************************
    * Possible Error using Constants to enable localization
    ********************************************************/
    const DATA_OK = 128;           //data is ok
    const DATA_MISSING = 1;        //data is missing
    const DATA_INVALID = 2;        //data is invalid
    const DATA_MISMATCH = 3;       //string mismatch between 2 string
    const REGISTRATION_FAILED = 4; //registration failed (db error)
    const USER_EXISTS = 5;         //user submitted already exists in database
    const USER_UNKNOWN = 6;        //user unknown (user name OR password Error)

    /**
    * Used to generated a unique token for each user
    * @var string
    */
    protected $secretKey = 'This is my hidden secret key'; //you should change this phrase

    /**
    * The Constructor initialize the db connection
    */
    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_errno || ! $this->conn->set_charset(DB_CHARSET)) {
            die("Sorry, no database connection.");
        }
    }

    /**
    * Return the regular expressions (can be use to match PHP and HTML5 regular expression)
    * @param  string $name an specified regular expression
    * @return mixed
    */
    public function getRegexp($name = null)
    {
        if (is_null($name)) {
            return self::$regexp;
        }
        if (isset(self::$regexp[$name])) {
            return self::$regexp[$name];
        }
        return null;
    }

    /**
    * Return the errors
    * @param  string $name an specified error
    * @return mixed
    */
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

    /**
    * check to see if the email is valid
    * @param  string  $str  the email to test
    * @return mixed  return the status to work with filter_* function
    */
    public static function isValidEmail($str = null)
    {
        if (is_null($str)) {
            return null;
        }
        $str = filter_var($str, FILTER_VALIDATE_EMAIL);
        if (! $str || 64 < strlen($str)) {
            return false;
        }

        return $str;
    }

    /**
    * check to see if the password is valid
    * @param  string  $str the password to test
    * @return mixed        the status to work with filter_* function
    */
    public static function isValidPassword($str = null)
    {
        if (is_null($str)) {
            return null;
        }
        $str = filter_var(
            $str,
            FILTER_VALIDATE_REGEXP,
            array('options' => array('regexp' => '/'.self::$regexp['user_password'].'/'))
        );
        if (! $str) {
            return false;
        }

        return $str;
    }

    /**
    * check to see if the username is valid
    * @param  string  $str the username to test
    * @return mixed        the status to work with filter_* function
    */
    public static function isValidUserName($str = null)
    {
        if (is_null($str)) {
            return null;
        }
        $str = filter_var(
            $str,
            FILTER_VALIDATE_REGEXP,
            array('options' => array('regexp' => '/'.self::$regexp['user_name'].'/'))
        );
        if (! $str) {
            return false;
        }

        return $str;
    }

    /**
    * return the user data
    * @param str $login the user name
    *
    * @return array the user info
    */
    protected function getUserByName($login)
    {
        $login = $this->conn->real_escape_string($login);
        $res = $this->conn->query("SELECT * FROM users WHERE user_name = '$login'");
        if ($res->num_rows != 1) {
            return array();
        }
        return $res->fetch_assoc();
    }

    /**
    * check if the user_name is unique in the users table
    * @param str $login the user name
    *
    * @return boolean
    */
    protected function isUniqueUsername($login)
    {
        $login = $this->conn->real_escape_string($login);
        $res = $this->conn->query(
            "SELECT COUNT(user_id) FROM users WHERE user_name = '$login'"
        );
        $count = $res->fetch_array(MYSQLI_NUM);
        return (int) $count[0];   
    }

    /**
    * check if the email is unique in the users table
    * @param str $login the user name
    *
    * @return boolean
    */
    protected function isUniqueEmail($email)
    {
        $email = $this->conn->real_escape_string($email);
        $res = $this->conn->query(
            "SELECT COUNT(user_id) FROM users WHERE user_email = '$email'"
        );
        $count = $res->fetch_array(MYSQLI_NUM);
        return (int) $count[0];
    }

    /**
    * is a user already with the given login OR email exists in the database
    * @param str $login the user name
    * @param str $email the user email
    *
    * @return boolean
    */
    protected function isUserExists($login, $email)
    {
        $login = $this->conn->real_escape_string($login);
        $email = $this->conn->real_escape_string($email);
        $res = $this->conn->query(
            "SELECT COUNT(user_id) FROM users WHERE user_name = '$login' OR user_email = '$email'"
        );
        $count = $res->fetch_array(MYSQLI_NUM);
        return (int) $count[0];
    }

    /**
     * generate a unique token
     * @param  string $login a string to generate the token with
     * @return string        the generated token
     */
    protected function generateToken($login)
    {
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $timestamp = time();
        $secret = sha1($login.'|'.$this->secretKey.'|'.$userAgent.'|'.$timestamp);
        return $login.'|'.$timestamp.'|'.$secret;
    }

    /**
     * validate a token against itself and against time
     * which makes session timeout possible
     * @param  string $str the token to be validated
     * @return boolean
     */
    protected function isValidToken($str)
    {
        $auth = explode('|', $str);
        $userAgent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (
            count($auth) != 3 ||
            strtotime('NOW - 30 MINUTES') > $auth[1] ||
            sha1($auth[0].'|'.$this->secretKey.'|'.$userAgent.'|'.$auth[1]) != $auth[2]
        ) {
            return false;
        }
        return true;
    }

    /**
     * tell if a value is null, false or set
     * @param  mixed $str the value to valid
     * @return int
     */
    public static function isDataValid($value = null)
    {
        if (is_null($value)) {
            return self::DATA_MISSING;
        } elseif (! $value) {
            return self::DATA_INVALID;
        }
        return self::DATA_OK;
    }
}
