<?php

/**
 * LoginModel
 * The login part of the model: Handles the login / logout / registration stuff
 */
class LoginModel
{
    /** @var Database $database The database connection */
    private $database;

    /**
     * Constructor, expects a Database connection
     * @param Database $database The Database object
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Login process (for DEFAULT user accounts).
     * @param $user_name string The user's name
     * @param $user_password string The user's password
     * @param $set_remember_me_cookie mixed Marker for usage of remember-me cookie feature
     * @return bool success state
     */
    public function login($user_name, $user_password, $set_remember_me_cookie = null)
    {
        // we do negative-first checks here, for simplicity empty username and empty password in one line
        if (empty($user_name) OR empty($user_password)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY);
            return false;
        }

        // get all data of that user (to later check if password and password_hash fit)
        $result = $this->getUserDataByUsername($user_name);

        // Check if that user exists. We don't give back a cause in the feedback to avoid giving an attacker details.
        if (!$result) {
            Session::add('feedback_negative', FEEDBACK_LOGIN_FAILED);
            return false;
        }

        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if (($result->user_failed_logins >= 3) AND ($result->user_last_failed_login > (time() - 30))) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_WRONG_3_TIMES);
            return false;
        }

        // if hash of provided password does NOT match the hash in the database: +1 failed-login counter
        if (!password_verify($user_password, $result->user_password_hash)) {
            $this->incrementFailedLoginCounterOfUser($user_name);
            // we say "password wrong" here, but less details like "login failed" would be better (= less information)
            Session::add('feedback_negative', FEEDBACK_PASSWORD_WRONG);
            return false;
        }

        // from here we assume that the password hash fits the database password hash, as password_verify() was true

        // if user is not active (= has not verified account by verification mail)
        if ($result->user_active != 1) {
            Session::add('feedback_negative', FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET);
            return false;
        }

        // reset the failed login counter for that user (if necessary)
        if ($result->user_last_failed_login > 0) {
            $this->resetFailedLoginCounterOfUser($user_name);
        }

        // save timestamp of this login in the database line of that user
        $this->saveTimestampOfLoginOfUser($user_name);

        // if user has checked the "remember me" checkbox, then write token into database and into cookie
        if ($set_remember_me_cookie) {
            $this->setRememberMeInDatabaseAndCookie($result->user_id);
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        $this->setSuccessfulLoginIntoSession(
            $result->user_id, $result->user_name, $result->user_email, $result->user_account_type
        );

        // return true to make clear the login was successful
        // maybe do this in dependence of setSuccessfulLoginIntoSession ?
        return true;
    }

    /**
     * Gets the user's data
     * @param $user_name string User's name
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public function getUserDataByUsername($user_name)
    {
        $sql = "SELECT user_id, user_name, user_email, user_password_hash, user_active, user_account_type,
                       user_failed_logins, user_last_failed_login
                  FROM users
                 WHERE (user_name = :user_name OR user_email = :user_name)
                       AND user_provider_type = :provider_type
                 LIMIT 1";
        $query = $this->database->prepare($sql);

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $query->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }

    /**
     * Gets the user's data by user's id and a token (used by login-via-cookie process)
     * @param $user_id
     * @param $token
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public function getUserDataByUserIdAndToken($user_id, $token)
    {
        // get real token from database (and all other data)
        $query = $this->database->prepare("SELECT user_id, user_name, user_email, user_password_hash, user_active,
                                          user_account_type,  user_has_avatar, user_failed_logins, user_last_failed_login
                                     FROM users
                                     WHERE user_id = :user_id
                                       AND user_remember_me_token = :user_remember_me_token
                                       AND user_remember_me_token IS NOT NULL
                                       AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_id' => $user_id, ':user_remember_me_token' => $token, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }

    /**
     * The real login process: The user's data is written into the session
     * Cheesy name, maybe rename
     * Also maybe refactoring this, using an array
     */
    public function setSuccessfulLoginIntoSession($user_id, $user_name, $user_email, $user_account_type)
    {
        Session::init();
        Session::set('user_id', $user_id);
        Session::set('user_name', $user_name);
        Session::set('user_email', $user_email);
        Session::set('user_account_type', $user_account_type);
        Session::set('user_provider_type', 'DEFAULT');

        // get and set avatars
        Session::set('user_avatar_file', $this->getPublicUserAvatarFilePathByUserId($user_id));
        Session::set('user_gravatar_image_url', $this->getGravatarLinkByEmail($user_email));

        // finally, set user as logged-in
        Session::set('user_logged_in', true);
    }

    /**
     * Increments the failed-login counter of a user
     * @param $user_name
     */
    public function incrementFailedLoginCounterOfUser($user_name)
    {
        $sql = "UPDATE users
                   SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                 WHERE user_name = :user_name OR user_email = :user_name
                 LIMIT 1";
        $sth = $this->database->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_failed_login' => time() ));
    }

    /**
     * Resets the failed-login counter of a user back to 0
     * @param $user_name
     */
    public function resetFailedLoginCounterOfUser($user_name)
    {
        $sql = "UPDATE users
                   SET user_failed_logins = 0, user_last_failed_login = NULL
                 WHERE user_name = :user_name AND user_failed_logins != 0
                 LIMIT 1";
        $sth = $this->database->prepare($sql);
        $sth->execute(array(':user_name' => $user_name));
    }

    /**
     * Write timestamp of this login into database (we only write a "real" login via login form into the database,
     * not the session-login on every page request
     * @param $user_name
     */
    public function saveTimestampOfLoginOfUser($user_name)
    {
        $sql = "UPDATE users SET user_last_login_timestamp = :user_last_login_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $sth = $this->database->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_login_timestamp' => time()));
    }

    /**
     * Write remember-me token into database and into cookie
     * Maybe splitting this into database and cookie part ?
     * @param $user_id
     */
    public function setRememberMeInDatabaseAndCookie($user_id)
    {
        // generate 64 char random string
        $random_token_string = hash('sha256', mt_rand());

        // write that token into database
        $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
        $sth = $this->database->prepare($sql);
        $sth->execute(array(':user_remember_me_token' => $random_token_string, ':user_id' => $user_id));

        // generate cookie string that consists of user id, random string and combined hash of both
        $cookie_string_first_part = $user_id . ':' . $random_token_string;
        $cookie_string_hash = hash('sha256', $cookie_string_first_part);
        $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

        // set cookie
        setcookie('remember_me', $cookie_string, time() + COOKIE_RUNTIME, COOKIE_PATH);
    }

    /**
     * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
     * TODO add throttling here ?
     * @param $cookie string The cookie "remember_me"
     * @return bool success state
     */
    public function loginWithCookie($cookie)
    {
        // do we have a cookie ?
        if (!$cookie) {
            Session::add('feedback_negative', FEEDBACK_COOKIE_INVALID);
            return false;
        }

        // check cookie's contents, check if cookie contents belong together
        list ($user_id, $token, $hash) = explode(':', $cookie);
        if ($hash !== hash('sha256', $user_id . ':' . $token)) {
            Session::add('feedback_negative', FEEDBACK_COOKIE_INVALID);
            return false;
        }

        // do not log in when token is empty
        if (empty($token)) {
            Session::add('feedback_negative', FEEDBACK_COOKIE_INVALID);
            return false;
        }

        // get data of user that has this id and this token
        $result = $this->getUserDataByUserIdAndToken($user_id, $token);

        // if user with that id and exactly that cookie token exists in database
        if ($result) {
            // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
            $this->setSuccessfulLoginIntoSession(
                $result->user_id, $result->user_name, $result->user_email, $result->user_account_type
            );
            // save timestamp of this login in the database line of that user
            $this->saveTimestampOfLoginOfUser($result->user_name);

            // NOTE: we don't set another remember_me-cookie here as the current cookie should always
            // be invalid after a certain amount of time, so the user has to login with username/password
            // again from time to time. This is good and safe ! ;)

            Session::add('feedback_positive', FEEDBACK_COOKIE_LOGIN_SUCCESSFUL);
            return true;
        } else {
            Session::add('feedback_negative', FEEDBACK_COOKIE_INVALID);
            return false;
        }
    }

    /**
     * Log out process: delete cookie, delete session
     */
    public function logout()
    {
        $this->deleteCookie();
        Session::destroy();
    }

    /**
     * Deletes the cookie
     * Sets the remember-me-cookie to ten years ago (3600sec * 24 hours * 365 days * 10).
     * that's obviously the best practice to kill a cookie @see http://stackoverflow.com/a/686166/1114320
     */
    public function deleteCookie()
    {
        setcookie('remember_me', false, time() - (3600 * 24 * 3650), COOKIE_PATH);
    }

    /**
     * Returns the current state of the user's login
     * @return bool user's login status
     */
    public function isUserLoggedIn()
    {
        return Session::userIsLoggedIn();
    }

    /**
     * Edit the user's name, provided in the editing form
     * @param $new_user_name string The new username
     * @return bool success status
     */
    public function editUserName($new_user_name)
    {
        // new username provided ?
        if (empty($new_user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_FIELD_EMPTY);
            return false;
        }

        // new username same as old one ?
        if ($new_user_name == Session::get('user_name')) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_SAME_AS_OLD_ONE);
            return false;
        }

        // username cannot be empty and must be azAZ09 and 2-64 characters
        if (!preg_match("/^[a-zA-Z0-9]{2,64}$/", $new_user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN);
            return false;
        }

        // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
        $new_user_name = substr(strip_tags($new_user_name), 0, 64);

        // check if new username already exists
        if ($this->doesUsernameAlreadyExist($new_user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_ALREADY_TAKEN);
            return false;
        }

        $status_of_action = $this->saveNewUserName(Session::get('user_id'), $new_user_name);
        if ($status_of_action) {
            Session::set('user_name', $new_user_name);
            Session::add('feedback_positive', FEEDBACK_USERNAME_CHANGE_SUCCESSFUL);
            return true;
        }

        // default fallback
        Session::add('feedback_negative', FEEDBACK_UNKNOWN_ERROR);
        return false;
    }

    public function doesUsernameAlreadyExist($user_name)
    {
        $query = $this->database->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $user_name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public function doesEmailAlreadyExist($user_email)
    {
        $query = $this->database->prepare("SELECT user_id FROM users WHERE user_email = :user_email LIMIT 1");
        $query->execute(array(':user_email' => $user_email));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public function saveNewUserName($user_id, $new_user_name)
    {
        $query = $this->database->prepare("UPDATE users SET user_name = :user_name WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_name' => $new_user_name, ':user_id' => $user_id));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public function saveNewEmailAddress($user_id, $new_user_email)
    {
        $query = $this->database->prepare("UPDATE users SET user_email = :user_email WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_email' => $new_user_email, ':user_id' => $user_id));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }

    /**
     * Edit the user's email
     * @param $new_user_email
     * @return bool success status
     */
    public function editUserEmail($new_user_email)
    {
        // email provided ?
        if (empty($new_user_email)) {
            Session::add('feedback_negative', FEEDBACK_EMAIL_FIELD_EMPTY);
            return false;
        }

        // check if new email is same like the old one
        if ($new_user_email == Session::get('user_email')) {
            Session::add('feedback_negative', FEEDBACK_EMAIL_SAME_AS_OLD_ONE);
            return false;
        }

        // user's email must be in valid email format
        if (!filter_var($new_user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN);
            return false;
        }

        // cut email length (everything else is spam and should later be deleted)
        // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
        // TODO is this even necessary anymore as we use FILTER_VALIDATE_EMAIL above ?
        $new_user_email = substr(strip_tags($new_user_email), 0, 254);

        // check if user's email already exists
        if ($this->doesEmailAlreadyExist($new_user_email)) {
            Session::add('feedback_negative', FEEDBACK_USER_EMAIL_ALREADY_TAKEN);
            return false;
        }

        // write to database, if successful ...
        // ... then write new email to session, Gravatar too (as this relies to the user's email address)
        if ($this->saveNewEmailAddress(Session::get('user_id'), $new_user_email)) {
            Session::set('user_email', $new_user_email);
            Session::set('user_gravatar_image_url', $this->getGravatarLinkByEmail($new_user_email));
            Session::add('feedback_positive', FEEDBACK_EMAIL_CHANGE_SUCCESSFUL);
            return true;
        }

        Session::add('feedback_negative', FEEDBACK_UNKNOWN_ERROR);
        return false;
    }

    /**
     * handles the entire registration process for DEFAULT users (not for people who register with
     * 3rd party services, like facebook) and creates a new user in the database if everything is fine
     * @return boolean Gives back the success status of the registration
     */
    public function registerNewUser()
    {
        // perform all necessary form checks
        if (!CaptchaModel::checkCaptcha(Request::post('captcha'))) {
            $_SESSION["feedback_negative"][] = FEEDBACK_CAPTCHA_WRONG;
        } elseif (empty($_POST['user_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_FIELD_EMPTY;
        } elseif (empty($_POST['user_password_new']) OR empty($_POST['user_password_repeat'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_FIELD_EMPTY;
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_REPEAT_WRONG;
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_TOO_SHORT;
        } elseif (strlen($_POST['user_name']) > 64 OR strlen($_POST['user_name']) < 2) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG;
        } elseif (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $_POST['user_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN;
        } elseif (empty($_POST['user_email'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_FIELD_EMPTY;
        } elseif (strlen($_POST['user_email']) > 254) {
            // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
            $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_TOO_LONG;
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN;
        } elseif (!empty($_POST['user_name'])
            AND strlen($_POST['user_name']) <= 64
            AND strlen($_POST['user_name']) >= 2
            AND preg_match('/^[a-zA-Z0-9]{2,64}$/', $_POST['user_name'])
            AND !empty($_POST['user_email'])
            AND strlen($_POST['user_email']) <= 254
            AND filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            AND !empty($_POST['user_password_new'])
            AND !empty($_POST['user_password_repeat'])
            AND ($_POST['user_password_new'] === $_POST['user_password_repeat'])) {

            // clean the input
            $user_name = strip_tags($_POST['user_name']);
            $user_email = strip_tags($_POST['user_email']);

            // crypt the password with the PHP 5.5's password_hash() function, results in a 60 character hash string.
            // @see php.net/manual/en/function.password-hash.php for more, especially for potential options
            $user_password_hash = password_hash($_POST['user_password_new'], PASSWORD_DEFAULT);

            // check if username already exists
            $query = $this->database->prepare("SELECT * FROM users WHERE user_name = :user_name LIMIT 1");
            $query->execute(array(':user_name' => $user_name));
            $count =  $query->rowCount();
            if ($count == 1) {
                $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_ALREADY_TAKEN;
                return false;
            }

            // check if email already exists
            $query = $this->database->prepare("SELECT user_id FROM users WHERE user_email = :user_email LIMIT 1");
            $query->execute(array(':user_email' => $user_email));
            $count =  $query->rowCount();
            if ($count == 1) {
                $_SESSION["feedback_negative"][] = FEEDBACK_USER_EMAIL_ALREADY_TAKEN;
                return false;
            }

            // generate random hash for email verification (40 char string)
            $user_activation_hash = sha1(uniqid(mt_rand(), true));
            // generate integer-timestamp for saving of account-creating date
            $user_creation_timestamp = time();

            // write new users data into database
            $sql = "INSERT INTO users (user_name, user_password_hash, user_email, user_creation_timestamp, user_activation_hash, user_provider_type)
                    VALUES (:user_name, :user_password_hash, :user_email, :user_creation_timestamp, :user_activation_hash, :user_provider_type)";
            $query = $this->database->prepare($sql);
            $query->execute(array(':user_name' => $user_name,
                                  ':user_password_hash' => $user_password_hash,
                                  ':user_email' => $user_email,
                                  ':user_creation_timestamp' => $user_creation_timestamp,
                                  ':user_activation_hash' => $user_activation_hash,
                                  ':user_provider_type' => 'DEFAULT'));
            $count =  $query->rowCount();
            if ($count != 1) {
                $_SESSION["feedback_negative"][] = FEEDBACK_ACCOUNT_CREATION_FAILED;
                return false;
            }

            // get user_id of the user that has been created, to keep things clean we DON'T use lastInsertId() here
            $query = $this->database->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
            $query->execute(array(':user_name' => $user_name));
            if ($query->rowCount() != 1) {
                $_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
                return false;
            }
            $result_user_row = $query->fetch();
            $user_id = $result_user_row->user_id;

            // send verification email, if verification email sending failed: instantly delete the user
            if ($this->sendVerificationEmail($user_id, $user_email, $user_activation_hash)) {
                $_SESSION["feedback_positive"][] = FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED;
                return true;
            } else {
                $query = $this->database->prepare("DELETE FROM users WHERE user_id = :last_inserted_id");
                $query->execute(array(':last_inserted_id' => $user_id));
                $_SESSION["feedback_negative"][] = FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED;
                return false;
            }
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
        }
        // default return, returns only true of really successful (see above)
        return false;
    }

    /**
     * Sends the verification email (to confirm the account)
     * @param int $user_id user's id
     * @param string $user_email user's email
     * @param string $user_activation_hash user's mail verification hash string
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    private function sendVerificationEmail($user_id, $user_email, $user_activation_hash)
    {
        // create email body
        $body = EMAIL_VERIFICATION_CONTENT . EMAIL_VERIFICATION_URL . '/' . urlencode($user_id) . '/'
                      . urlencode($user_activation_hash);

        // create instance of Mail class, try sending and check
        $mail = new Mail;
        $mail_sent = $mail->sendMail(
            $user_email, EMAIL_VERIFICATION_FROM_EMAIL, EMAIL_VERIFICATION_FROM_NAME, EMAIL_VERIFICATION_SUBJECT, $body
        );

        if ($mail_sent) {
            Session::add('feedback_positive', FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL);
            return true;
        }

        Session::add('feedback_negative', FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR . $mail->getError() );
        return false;
    }

    /**
     * checks the email/verification code combination and set the user's activation status to true in the database
     * @param int $user_id user id
     * @param string $user_activation_verification_code verification token
     * @return bool success status
     */
    public function verifyNewUser($user_id, $user_activation_verification_code)
    {
        $sql = "UPDATE users SET user_active = 1, user_activation_hash = NULL
                WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash LIMIT 1";
        $query = $this->database->prepare($sql);
        $query->execute(array(':user_id' => $user_id, ':user_activation_hash' => $user_activation_verification_code));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL);
            return true;
        }

        Session::add('feedback_negative', FEEDBACK_ACCOUNT_ACTIVATION_FAILED);
        return false;
    }

    /**
     * Gets a gravatar image link from given email address
     *
     * Gravatar is the #1 (free) provider for email address based global avatar hosting.
     * The URL (or image) returns always a .jpg file ! For deeper info on the different parameter possibilities:
     * @see http://gravatar.com/site/implement/images/
     * @source http://gravatar.com/site/implement/images/php/
     *
     * This method will return something like http://www.gravatar.com/avatar/79e2e5b48aec07710c08d50?s=80&d=mm&r=g
     * Note: the url does NOT have something like ".jpg" ! It works without.
     *
     * Set the configs inside the application/config/ files.
     *
     * @param string $email The email address
     * @return string
     */
    public function getGravatarLinkByEmail($email)
    {
        return 'http://www.gravatar.com/avatar/' .
               md5( strtolower( trim( $email ) ) ) .
               '?s=' . AVATAR_SIZE . '&d=' . GRAVATAR_DEFAULT_IMAGESET . '&r=' . GRAVATAR_RATING;
    }

    /**
     * Gets the user's avatar file path
     * @param $user_id integer The user's id
     * @return string avatar picture path
     */
    public function getPublicUserAvatarFilePathByUserId($user_id)
    {
        $query = $this->database->prepare("SELECT user_has_avatar FROM users WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_id' => $user_id));

        if ($query->fetch()->user_has_avatar) {
            return URL . PATH_AVATARS_PUBLIC . $user_id . '.jpg';
        }

        return URL . PATH_AVATARS_PUBLIC . AVATAR_DEFAULT_IMAGE;
    }

    /**
     * Create an avatar picture (and checks all necessary things too)
     * @return bool success status
     */
    public function createAvatar()
    {
        if (!is_dir(PATH_AVATARS) OR !is_writable(PATH_AVATARS)) {
            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE;
            return false;
        }

        if (!isset($_FILES['avatar_file']) OR empty ($_FILES['avatar_file']['tmp_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED;
            return false;
        }

        // get the image width, height and mime type
        $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

        // if input file too big (>5MB)
        if ($_FILES['avatar_file']['size'] > 5000000 ) {
            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_UPLOAD_TOO_BIG;
            return false;
        }
        // if input file too small
        if ($image_proportions[0] < AVATAR_SIZE OR $image_proportions[1] < AVATAR_SIZE) {
            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_UPLOAD_TOO_SMALL;
            return false;
        }

        if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') {
            // create a jpg file in the avatar folder
            $target_file_path = PATH_AVATARS . $_SESSION['user_id'] . ".jpg";
            $this->resizeAvatarImage($_FILES['avatar_file']['tmp_name'], $target_file_path, AVATAR_SIZE, AVATAR_SIZE, AVATAR_JPEG_QUALITY);
            $query = $this->database->prepare("UPDATE users SET user_has_avatar = TRUE WHERE user_id = :user_id LIMIT 1");
            $query->execute(array(':user_id' => $_SESSION['user_id']));
            Session::set('user_avatar_file', $this->getPublicUserAvatarFilePathByUserId($_SESSION['user_id']));
            $_SESSION["feedback_positive"][] = FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL;
            return true;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE;
            return false;
        }
    }

    /**
     * Resize avatar image (while keeping aspect ratio and cropping it off sexy)
     *
     * @param string $source_image The location to the original raw image.
     * @param string $destination The location to save the new image.
     * @param int $final_width The desired width of the new image
     * @param int $final_height The desired height of the new image.
     * @param int $quality The quality of the JPG to produce 1 - 100
     *
     * TODO currently we just allow .jpg
     *
     * @return bool success state
     */
    public function resizeAvatarImage($source_image, $destination, $final_width = 44, $final_height = 44, $quality = 85)
    {
        list($width, $height) = getimagesize($source_image);

        if (!$width || !$height) {
            return false;
        }

        //saving the image into memory (for manipulation with GD Library)
        $myImage = imagecreatefromjpeg($source_image);

        // calculating the part of the image to use for thumbnail
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        // copying the part into thumbnail, maybe edit this for square avatars
        $thumb = imagecreatetruecolor($final_width, $final_height);
        imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $final_width, $final_height, $smallestSide, $smallestSide);

        // save it as a .jpg file with our $destination_filename parameter
        imagejpeg($thumb, $destination, $quality);

        // delete "working copy"
        imagedestroy($thumb);

        if (file_exists($destination)) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Perform the necessary actions to send a password reset mail
     * @param $user_name_or_email string Username or user's email
     * @return bool success status
     */
    public function requestPasswordReset($user_name_or_email)
    {
        if (empty($user_name_or_email)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_EMAIL_FIELD_EMPTY);
            return false;
        }

        // check if that username exists
        $result = $this->getUserDataByUserNameOrEmail($user_name_or_email);
        if (!$result) {
            Session::add('feedback_negative', FEEDBACK_USER_DOES_NOT_EXIST);
            return false;
        }

        // generate integer-timestamp (to see when exactly the user (or an attacker) requested the password reset mail)
        // generate random hash for email password reset verification (40 char string)
        $temporary_timestamp = time();
        $user_password_reset_hash = sha1(uniqid(mt_rand(), true));

        // set token (= a random hash string and a timestamp) into database ...
        $token_set = $this->setPasswordResetDatabaseToken($result->user_name, $user_password_reset_hash, $temporary_timestamp);
        if (!$token_set) {
            return false;
        }

        // ... and send a mail to the user, containing a link with username and token hash string
        $mail_sent = $this->sendPasswordResetMail($result->user_name, $user_password_reset_hash, $result->user_email);
        if ($mail_sent) {
            return true;
        }

        // default return
        return false;
    }

    // hmmmm...
    public function getUserDataByUserNameOrEmail($user_name_or_email)
    {
        $query = $this->database->prepare("SELECT user_id, user_name, user_email FROM users
                                           WHERE (user_name = :user_name_or_email OR user_email = :user_name_or_email)
                                           AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_name_or_email' => $user_name_or_email, ':provider_type' => 'DEFAULT'));

        return $query->fetch();
    }

    /**
     * Set password reset token in database (for DEFAULT user accounts)
     * @param string $user_name username
     * @param string $user_password_reset_hash password reset hash
     * @param int $temporary_timestamp timestamp
     * @return bool success status
     */
    public function setPasswordResetDatabaseToken($user_name, $user_password_reset_hash, $temporary_timestamp)
    {
        // this could be formatted better
        $sql = "UPDATE users
                SET user_password_reset_hash = :user_password_reset_hash,
                    user_password_reset_timestamp = :user_password_reset_timestamp
                WHERE user_name = :user_name AND user_provider_type = :provider_type
                LIMIT 1";
        $query = $this->database->prepare($sql);
        $query->execute(array(
            ':user_password_reset_hash' => $user_password_reset_hash, ':user_name' => $user_name,
            ':user_password_reset_timestamp' => $temporary_timestamp, ':provider_type' => 'DEFAULT'
        ));

        // check if exactly one row was successfully changed
        if ($query->rowCount() == 1) {
            return true;
        }

        // fallback
        Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_TOKEN_FAIL);
        return false;
    }

    /**
     * send the password reset mail
     * @param string $user_name username
     * @param string $user_password_reset_hash password reset hash
     * @param string $user_email user email
     * @return bool success status
     */
    public function sendPasswordResetMail($user_name, $user_password_reset_hash, $user_email)
    {
        // create PHPMailer object here. This is easily possible as we auto-load the according class(es) via composer
        $mail = new PHPMailer;

        // please look into the config/config.php for much more info on how to use this!
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors, config this in config/config.php
            $mail->SMTPDebug = PHPMAILER_DEBUG_MODE;
            // Enable SMTP authentication
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            // Enable encryption, usually SSL/TLS
            if (defined('EMAIL_SMTP_ENCRYPTION')) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            // Specify host server
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Port = EMAIL_SMTP_PORT;
        } else {
            $mail->IsMail();
        }

        // build the email
        $mail->From = EMAIL_PASSWORD_RESET_FROM_EMAIL;
        $mail->FromName = EMAIL_PASSWORD_RESET_FROM_NAME;
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_PASSWORD_RESET_SUBJECT;
        $link = EMAIL_PASSWORD_RESET_URL . '/' . urlencode($user_name) . '/' . urlencode($user_password_reset_hash);
        $mail->Body = EMAIL_PASSWORD_RESET_CONTENT . ' ' . $link;

        // send the mail
        if($mail->Send()) {
            $_SESSION["feedback_positive"][] = FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL;
            return true;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR . $mail->ErrorInfo;
            return false;
        }
    }

    /**
     * Verifies the password reset request via the verification hash token (that's only valid for one hour)
     * @param string $user_name Username
     * @param string $verification_code Hash token
     * @return bool Success status
     */
    public function verifyPasswordReset($user_name, $verification_code)
    {
        // check if user-provided username + verification code combination exists
        $sql = "SELECT user_id, user_password_reset_timestamp
                  FROM users
                 WHERE user_name = :user_name
                       AND user_password_reset_hash = :user_password_reset_hash
                       AND user_provider_type = :user_provider_type
                 LIMIT 1";
        $query = $this->database->prepare($sql);
        $query->execute(array(
            ':user_password_reset_hash' => $verification_code, ':user_name' => $user_name,
            ':user_provider_type' => 'DEFAULT'
        ));

        // if this user with exactly this verification hash code does NOT exist
        if ($query->rowCount() != 1) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST);
            return false;
        }

        // get result row (as an object)
        $result_user_row = $query->fetch();

        // 3600 seconds are 1 hour
        $timestamp_one_hour_ago = time() - 3600;

        // if password reset request was sent within the last hour (this timeout is for security reasons)
        if ($result_user_row->user_password_reset_timestamp > $timestamp_one_hour_ago) {
            // verification was successful
            Session::add('feedback_positive', FEEDBACK_PASSWORD_RESET_LINK_VALID);
            return true;
        } else {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_LINK_EXPIRED);
            return false;
        }
    }

    /**
     * Set the new password (for DEFAULT user, FACEBOOK-users don't have a password)
     * Please note: At this point the user has already pre-verified via verifyPasswordReset() (within one hour),
     * so we don't need to check again for the 60min-limit here. In this method we authenticate
     * via username & password-reset-hash from (hidden) form fields.
     * @param $user_name
     * @param $user_password_reset_hash
     * @param $user_password_new
     * @param $user_password_repeat
     *
     * @return bool success state of the password reset
     */
    public function setNewPassword($user_name, $user_password_reset_hash, $user_password_new, $user_password_repeat)
    {
        if (empty($user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_FIELD_EMPTY);
            return false;
        }
        if (empty($user_password_reset_hash)) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_TOKEN_MISSING);
            return false;
        }
        if (empty($user_password_new)) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_FIELD_EMPTY);
            return false;
        }
        if (empty($user_password_repeat)) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_FIELD_EMPTY);
            return false;
        }
        if ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_REPEAT_WRONG);
            return false;
        }
        if (strlen($user_password_new) < 6) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_TOO_SHORT);
            return false;
        }

        // crypt the user's password with the PHP 5.5+'s password_hash() function, result is a 60 character hash string
        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);

        // write user's new password hash into database, reset user_password_reset_hash
        if ($this->saveNewUserPassword($user_name, $user_password_hash, $user_password_reset_hash)) {
            Session::add('feedback_positive', FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL);
            return true;
        }

        // default return
        Session::add('feedback_negative', FEEDBACK_PASSWORD_CHANGE_FAILED);
        return false;
    }

    /**
     * @param $user_name
     * @param $user_password_hash
     * @param $user_password_reset_hash
     *
     * @return bool
     */
    public function saveNewUserPassword($user_name, $user_password_hash, $user_password_reset_hash)
    {
        $sql = "UPDATE users
                   SET user_password_hash = :user_password_hash,
                       user_password_reset_hash = NULL,
                       user_password_reset_timestamp = NULL
                 WHERE user_name = :user_name
                       AND user_password_reset_hash = :user_password_reset_hash
                       AND user_provider_type = :user_provider_type
                 LIMIT 1";
        $query = $this->database->prepare($sql);
        $query->execute(array(
            ':user_password_hash' => $user_password_hash, ':user_name' => $user_name,
            ':user_password_reset_hash' => $user_password_reset_hash, ':user_provider_type' => 'DEFAULT'
        ));

        // if successful
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Upgrades the user's account (for DEFAULT and FACEBOOK users)
     * Currently it's just the field user_account_type in the database that
     * can be 1 or 2 (maybe "basic" or "premium"). In this basic method we
     * simply increase this value to emulate an account upgrade.
     * Put some more complex stuff in here, maybe a pay-process or whatever you like.
     */
    public function changeAccountTypeUpgrade()
    {
        // in a real-world application you'll have a payment-process here or so

        $query = $this->database->prepare("UPDATE users SET user_account_type = 2 WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            // set account type in session to 2
            Session::set('user_account_type', 2);
            Session::add('feedback_positive', FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL);
            return true;
        }

        // default return
        Session::add('feedback_negative', FEEDBACK_ACCOUNT_UPGRADE_FAILED);
        return false;
    }

    /**
     * Downgrades the user's account (for DEFAULT and FACEBOOK users)
     * Currently it's just the field user_account_type in the database that
     * can be 1 or 2 (maybe "basic" or "premium"). In this basic method we
     * simply decrease this value to emulate an account downgrade.
     * Put some more complex stuff in here, maybe a pay-process or whatever you like.
     */
    public function changeAccountTypeDowngrade()
    {
        $query = $this->database->prepare("UPDATE users SET user_account_type = 1 WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            // set account type in session to 1
            Session::set('user_account_type', 1);
            Session::add('feedback_positive', FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL);
            return true;
        }

        // default return
        Session::add('feedback_negative', FEEDBACK_ACCOUNT_DOWNGRADE_FAILED);
        return false;
    }
}
