<?php

/**
 * LoginModel
 *
 * The login part of the model: Handles the login / logout stuff
 */
class LoginModel
{
    /**
     * Login process (for DEFAULT user accounts).
     *
     * @param $user_name string The user's name
     * @param $user_password string The user's password
     * @param $set_remember_me_cookie mixed Marker for usage of remember-me cookie feature
     *
     * @return bool success state
     */
    public static function login($user_name, $user_password, $set_remember_me_cookie = null)
    {
        // we do negative-first checks here, for simplicity empty username and empty password in one line
        if (empty($user_name) OR empty($user_password)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

	    // checks if user exists, if login is not blocked (due to failed logins) and if password fits the hash
	    $result = self::validateAndGetUser($user_name, $user_password);

        // check if that user exists. We don't give back a cause in the feedback to avoid giving an attacker details.
        if (!$result) {
            //No Need to give feedback here since whole validateAndGetUser controls gives a feedback
            return false;
        }

        // stop the user's login if account has been soft deleted
        if ($result->user_deleted == 1) {
            Session::add('feedback_negative', Text::get('FEEDBACK_DELETED'));
            return false;
        }

        // stop the user from logging in if user has a suspension, display how long they have left in the feedback.
        if ($result->user_suspension_timestamp != null && $result->user_suspension_timestamp - time() > 0) {
            $suspensionTimer = Text::get('FEEDBACK_ACCOUNT_SUSPENDED') . round(abs($result->user_suspension_timestamp - time())/60/60, 2) . " hours left";
            Session::add('feedback_negative', $suspensionTimer);
            return false;
        }

        // reset the failed login counter for that user (if necessary)
        if ($result->user_last_failed_login > 0) {
            self::resetFailedLoginCounterOfUser($result->user_name);
        }

        // save timestamp of this login in the database line of that user
        self::saveTimestampOfLoginOfUser($result->user_name);

        // if user has checked the "remember me" checkbox, then write token into database and into cookie
        if ($set_remember_me_cookie) {
            self::setRememberMeInDatabaseAndCookie($result->user_id);
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        self::setSuccessfulLoginIntoSession(
            $result->user_id, $result->user_name, $result->user_email, $result->user_account_type
        );

        // return true to make clear the login was successful
        // maybe do this in dependence of setSuccessfulLoginIntoSession ?
        return true;
    }

	/**
	 * Validates the inputs of the users, checks if password is correct etc.
	 * If successful, user is returned
	 *
	 * @param $user_name
	 * @param $user_password
	 *
	 * @return bool|mixed
	 */
	private static function validateAndGetUser($user_name, $user_password)
	{
		// brute force attack mitigation: use session failed login count and last failed login for not found users.
		// block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
		// (limits user searches in database)
		if (Session::get('failed-login-count') >= 3 AND (Session::get('last-failed-login') > (time() - 30))) {
			Session::add('feedback_negative', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
			return false;
		}
		
		// get all data of that user (to later check if password and password_hash fit)
		$result = UserModel::getUserDataByUsername($user_name);

        // check if that user exists. We don't give back a cause in the feedback to avoid giving an attacker details.
        // brute force attack mitigation: reset failed login counter because of found user
        if (!$result){
            // increment the user not found count, helps mitigate user enumeration
            self::incrementUserNotFoundCounter();
            // user does not exist, but we won't to give a potential attacker this details, so we just use a basic feedback message
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
			return false;
		}

		// block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
		if (($result->user_failed_logins >= 3) AND ($result->user_last_failed_login > (time() - 30))) {
			Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
			return false;
		}

		// if hash of provided password does NOT match the hash in the database: +1 failed-login counter
		if (!password_verify($user_password, $result->user_password_hash)) {
			self::incrementFailedLoginCounterOfUser($result->user_name);
			Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG')); 
			return false;
		}

		// if user is not active (= has not verified account by verification mail)
		if ($result->user_active != 1) {
			Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
			return false;
		}

        // reset the user not found counter
        self::resetUserNotFoundCounter();

		return $result;
	}

    /**
     * Reset the failed-login-count to 0.
     * Reset the last-failed-login to an empty string.
     */
    private static function resetUserNotFoundCounter()
    {
        Session::set('failed-login-count', 0);
        Session::set('last-failed-login', '');
    }

    /**
     * Increment the failed-login-count by 1.
     * Add timestamp to last-failed-login.
     */
    private static function incrementUserNotFoundCounter()
    {
        // Username enumeration prevention: set session failed login count and last failed login for users not found
        Session::set('failed-login-count', Session::get('failed-login-count') + 1);
        Session::set('last-failed-login', time());
    }

    /**
     * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
     * TODO add throttling here ?
     *
     * @param $cookie string The cookie "remember_me"
     *
     * @return bool success state
     */
    public static function loginWithCookie($cookie)
    {
        // do we have a cookie ?
        if (!$cookie) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // before list(), check it can be split into 3 strings.
        if(count (explode(':', $cookie)) !== 3){
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // check cookie's contents, check if cookie contents belong together or token is empty
        list ($user_id, $token, $hash) = explode(':', $cookie);

        // decrypt user id
        $user_id = Encryption::decrypt($user_id);

        if ($hash !== hash('sha256', $user_id . ':' . $token) OR empty($token) OR empty($user_id)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // get data of user that has this id and this token
        $result = UserModel::getUserDataByUserIdAndToken($user_id, $token);

        // if user with that id and exactly that cookie token exists in database
        if ($result) {
            // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
            self::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type);
            // save timestamp of this login in the database line of that user
            self::saveTimestampOfLoginOfUser($result->user_name);

            // NOTE: we don't set another remember_me-cookie here as the current cookie should always
            // be invalid after a certain amount of time, so the user has to login with username/password
            // again from time to time. This is good and safe ! ;)

            Session::add('feedback_positive', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }
    }

    /**
     * Log out process: delete cookie, delete session
     */
    public static function logout()
    {
        $user_id = Session::get('user_id');

        self::deleteCookie($user_id);

        Session::destroy();
        Session::updateSessionId($user_id);
    }

    /**
     * The real login process: The user's data is written into the session.
     * Cheesy name, maybe rename. Also maybe refactoring this, using an array.
     *
     * @param $user_id
     * @param $user_name
     * @param $user_email
     * @param $user_account_type
     */
    public static function setSuccessfulLoginIntoSession($user_id, $user_name, $user_email, $user_account_type)
    {
        Session::init();

        // remove old and regenerate session ID.
        // It's important to regenerate session on sensitive actions,
        // and to avoid fixated session.
        // e.g. when a user logs in
        session_regenerate_id(true);
        $_SESSION = array();

        Session::set('user_id', $user_id);
        Session::set('user_name', $user_name);
        Session::set('user_email', $user_email);
        Session::set('user_account_type', $user_account_type);
        Session::set('user_provider_type', 'DEFAULT');

        // get and set avatars
        Session::set('user_avatar_file', AvatarModel::getPublicUserAvatarFilePathByUserId($user_id));
        Session::set('user_gravatar_image_url', AvatarModel::getGravatarLinkByEmail($user_email));

        // finally, set user as logged-in
        Session::set('user_logged_in', true);

        // update session id in database
        Session::updateSessionId($user_id, session_id());

        // set session cookie setting manually,
        // Why? because you need to explicitly set session expiry, path, domain, secure, and HTTP.
        // @see https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Cookies
        setcookie(session_name(), session_id(), time() + Config::get('SESSION_RUNTIME'), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));

    }

    /**
     * Increments the failed-login counter of a user
     *
     * @param $user_name
     */
    public static function incrementFailedLoginCounterOfUser($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users
                   SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                 WHERE user_name = :user_name OR user_email = :user_name
                 LIMIT 1";
        $sth = $database->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_failed_login' => time() ));
    }

    /**
     * Resets the failed-login counter of a user back to 0
     *
     * @param $user_name
     */
    public static function resetFailedLoginCounterOfUser($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users
                   SET user_failed_logins = 0, user_last_failed_login = NULL
                 WHERE user_name = :user_name AND user_failed_logins != 0
                 LIMIT 1";
        $sth = $database->prepare($sql);
        $sth->execute(array(':user_name' => $user_name));
    }

    /**
     * Write timestamp of this login into database (we only write a "real" login via login form into the database,
     * not the session-login on every page request
     *
     * @param $user_name
     */
    public static function saveTimestampOfLoginOfUser($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $sth = $database->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_login_timestamp' => time()));
    }

    /**
     * Write remember-me token into database and into cookie
     * Maybe splitting this into database and cookie part ?
     *
     * @param $user_id
     */
    public static function setRememberMeInDatabaseAndCookie($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // generate 64 char random string
        $random_token_string = hash('sha256', mt_rand());

        // write that token into database
        $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
        $sth = $database->prepare($sql);
        $sth->execute(array(':user_remember_me_token' => $random_token_string, ':user_id' => $user_id));

        // generate cookie string that consists of user id, random string and combined hash of both
        // never expose the original user id, instead, encrypt it.
        $cookie_string_first_part = Encryption::encrypt($user_id) . ':' . $random_token_string;
        $cookie_string_hash       = hash('sha256', $user_id . ':' . $random_token_string);
        $cookie_string            = $cookie_string_first_part . ':' . $cookie_string_hash;
		
		// set cookie, and make it available only for the domain created on (to avoid XSS attacks, where the
        // attacker could steal your remember-me cookie string and would login itself).
        // If you are using HTTPS, then you should set the "secure" flag (the second one from right) to true, too.
        // @see http://www.php.net/manual/en/function.setcookie.php
        setcookie('remember_me', $cookie_string, time() + Config::get('COOKIE_RUNTIME'), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

    /**
     * Deletes the cookie
     * It's necessary to split deleteCookie() and logout() as cookies are deleted without logging out too!
     * Sets the remember-me-cookie to ten years ago (3600sec * 24 hours * 365 days * 10).
     * that's obviously the best practice to kill a cookie @see http://stackoverflow.com/a/686166/1114320
     *
     * @param string $user_id
     */
    public static function deleteCookie($user_id = null)
    {
        // is $user_id was set, then clear remember_me token in database
        if(isset($user_id)){

            $database = DatabaseFactory::getFactory()->getConnection();

            $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
            $sth = $database->prepare($sql);
            $sth->execute(array(':user_remember_me_token' => NULL, ':user_id' => $user_id));
        }

        // delete remember_me cookie in browser
        setcookie('remember_me', false, time() - (3600 * 24 * 3650), Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
    }

    /**
     * Returns the current state of the user's login
     *
     * @return bool user's login status
     */
    public static function isUserLoggedIn()
    {
        return Session::userIsLoggedIn();
    }
}
