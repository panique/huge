<?php

/**
 * Session class
 *
 * handles the session stuff. creates session when no one exists, sets and gets values, and closes the session
 * properly (=logout). Not to forget the check if the user is logged in or not.
 */
class Session
{
    /**
     * starts the session
     */
    public static function init()
    {
        // if no session exist, start the session
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * sets a specific value to a specific key of the session
     *
     * @param mixed $key key
     * @param mixed $value value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * gets/returns the value of a specific key of the session
     *
     * @param mixed $key Usually a string, right ?
     * @return mixed the key's value or nothing
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        	// filter the value for XSS vulnerabilities
        	return Filter::XSSFilter($value);
        }
    }

    /**
     * adds a value as a new array element to the key.
     * useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     */
    public static function add($key, $value)
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * deletes the session (= logs the user out)
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * update session id in database
     *
     * @access public
     * @static static method
     * @param  string $userId
     * @param  string $sessionId
     * @return string
     */
    public static function updateSessionId($userId, $sessionId = null){

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE users SET session_id = :session_id WHERE user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute(array(':session_id' => $sessionId, ":user_id" => $userId));

    }

    /**
     * checks for session concurrency
     *
     * This is done as the following:
     * UserA logs in with his session id('123') and it will be stored in the database.
     * Then, UserB logs in also using the same email and password of UserA from another PC,
     * and also store the session id('456') in the database
     *
     * Now, Whenever UserA performs any action,
     * You then check the session_id() against the last one stored in the database('456'),
     * If they don't match then log both of them out.
     *
     * @access public
     * @static static method
     * @return bool
     * @see Session::updateSessionId()
     * @see http://stackoverflow.com/questions/6126285/php-stop-concurrent-user-logins
     */
    public static function isConcurrentSessionExists(){

        $session_id = session_id();
        $userId     = Session::get('user_id');

        if(isset($userId) && isset($session_id)){

            $database = DatabaseFactory::getFactory()->getConnection();
            $sql = "SELECT session_id FROM users WHERE user_id = :user_id LIMIT 1";

            $query = $database->prepare($sql);
            $query->execute(array(":user_id" => $userId));

            $result = $query->fetch();
            $userSessionId = !empty($result)? $result->session_id: null;

            return $session_id !== $userSessionId;
        }

        return false;
    }

    /**
     * Checks if the user is logged in or not
     *
     * @return bool user's login status
     */
    public static function userIsLoggedIn()
    {
        return (self::get('user_logged_in') ? true : false);
    }
}
