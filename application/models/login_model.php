<?php

/**
 * Login_Model
 * handles the user's login, logout, username editing, password changing...
 */
class Login_Model
{
    /**
     * Constructor
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * login process (for DEFAULT user accounts). user who register/login with Facebook etc. are handled
     * somewhere else
     * TODO: hardcore refactoring
     * @return bool success state
     */
    public function login()
    {
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
            // get user's data
            // (we check if the password fits the password_hash via password_verify() some lines below)
            $sth = $this->db->prepare("SELECT user_id, 
                                              user_name, 
                                              user_email, 
                                              user_password_hash, 
                                              user_active, 
                                              user_account_type,
                                              user_failed_logins, 
                                              user_last_failed_login  
                                       FROM   users
                                       WHERE  user_name = :user_name
                                              OR user_email = :user_name
                                              AND user_provider_type = :provider_type");
            // DEFAULT is the marker for "normal" accounts (that have a password etc.)
            // There are other types of accounts that don't have passwords etc. (FACEBOOK)
            $sth->execute(array(':user_name' => $_POST['user_name'], ':provider_type' => 'DEFAULT'));

            $count =  $sth->rowCount();
            if ($count == 1) {
                // fetch one row (we only have one result)
                $result = $sth->fetch();
                
                if ( ($result->user_failed_logins >= 3) && ($result->user_last_failed_login > (time()-30)) ) {
                    $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_WRONG_3_TIMES;
                    return false;
                } else {

                    if (password_verify($_POST['user_password'], $result->user_password_hash)) {

                        if ($result->user_active == 1) {
                            // login
                            Session::init();
                            Session::set('user_logged_in', true);
                            Session::set('user_id', $result->user_id);
                            Session::set('user_name', $result->user_name);
                            Session::set('user_email', $result->user_email);
                            Session::set('user_account_type', $result->user_account_type);
                            Session::set('user_provider_type', 'DEFAULT');
                            
                            Session::set('user_avatar_file', $this->getUserAvatarFilePath());

                            // call the setGravatarImageUrl() method which writes gravatar urls into the session
                            $this->setGravatarImageUrl($result->user_email);

                            // reset the failed login counter for that user
                            $sql = "UPDATE users
                                    SET user_failed_logins = 0, user_last_failed_login = NULL
                                    WHERE user_id = :user_id AND user_failed_logins != 0";
                            $sth = $this->db->prepare($sql);
                            $sth->execute(array(':user_id' => $result->user_id));
                            
                            // if user has check the "remember me" checkbox, then write cookie
                            if (isset($_POST['user_rememberme'])) {
                                
                                // generate 64 char random string
                                $random_token_string = hash('sha256', mt_rand());
                                
                                $sth = $this->db->prepare("UPDATE users SET user_rememberme_token = :user_rememberme_token WHERE user_id = :user_id");
                                $sth->execute(array(':user_rememberme_token' => $random_token_string, ':user_id' => $result->user_id));
                                
                                // generate cookie string that consists of userid, randomstring and combined hash of both
                                $cookie_string_first_part = $result->user_id . ':' . $random_token_string;
                                $cookie_string_hash = hash('sha256', $cookie_string_first_part);        
                                $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;        

                                // set cookie
                                setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME, "/", COOKIE_DOMAIN);
                            }

                            return true;
                        } else {

                            $_SESSION["feedback_negative"][] = FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET;
                            return false;
                        }
                    } else {

                        // increment the failed login counter for that user
                        $sth = $this->db->prepare("UPDATE users "
                                . "SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login "
                                . "WHERE user_name = :user_name OR user_email = :user_name");
                        $sth->execute(array(':user_name' => $_POST['user_name'], ':user_last_failed_login' => time() ));

                        $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_WRONG;
                        return false;
                    }
                }
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_USER_DOES_NOT_EXIST;
                return false;
            }
        } elseif (empty($_POST['user_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_FIELD_EMPTY;
        } elseif (empty($_POST['user_password'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_FIELD_EMPTY;
        }
    }

    /**
     * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
     * TODO: hardcore refactoring
     * @return bool success state
     */
    public function loginWithCookie()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';

        if ($cookie) {
            list ($user_id, $token, $hash) = explode(':', $cookie);
            if ($hash !== hash('sha256', $user_id . ':' . $token)) {
                $_SESSION["feedback_negative"][] = FEEDBACK_COOKIE_INVALID;
                return false;
            }

            // do not log in when token is empty
            if (empty($token)) {
                $_SESSION["feedback_negative"][] = FEEDBACK_COOKIE_INVALID;
                return false;
            }

            // get real token from database (and all other data)
            $sth = $this->db->prepare("SELECT user_id,
                                              user_name,
                                              user_email,
                                              user_password_hash,
                                              user_active,
                                              user_account_type,
                                              user_has_avatar,
                                              user_failed_logins,
                                              user_last_failed_login
                                         FROM users
                                         WHERE user_id = :user_id
                                           AND user_rememberme_token = :user_rememberme_token
                                           AND user_rememberme_token IS NOT NULL
                                           AND user_provider_type = :provider_type");
            $sth->execute(array(':user_id' => $user_id,
                                ':user_rememberme_token' => $token,
                                ':provider_type' => 'DEFAULT'));

            $count =  $sth->rowCount();
            if ($count == 1) {
                // fetch one row (we only have one result)
                $result = $sth->fetch();
                // TODO: this block is same/similar to the one from login(), maybe we should put this in a method
                // write data into session
                Session::init();
                Session::set('user_logged_in', true);
                Session::set('user_id', $result->user_id);
                Session::set('user_name', $result->user_name);
                Session::set('user_email', $result->user_email);
                Session::set('user_account_type', $result->user_account_type);
                Session::set('user_provider_type', 'DEFAULT');
                Session::set('user_avatar_file', $this->getUserAvatarFilePath());

                // call the setGravatarImageUrl() method which writes gravatar urls into the session
                $this->setGravatarImageUrl($result->user_email);

                // NOTE: we don't set another rememberme-cookie here as the current cookie should always
                // be invalid after a certain amount of time, so the user has to login with username/password
                // again from time to time. This is good and safe ! ;)
                $_SESSION["feedback_positive"][] = FEEDBACK_COOKIE_LOGIN_SUCCESSFUL;
                return true;
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_COOKIE_INVALID;
                return false;
            }
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_COOKIE_INVALID;
            return false;
        }
    }

    /**
     * @return bool
     */
    public function loginWithFacebook()
    {
        // instantiate the facebook object
        $facebook = new Facebook(array(
            'appId'  => FACEBOOK_LOGIN_APP_ID,
            'secret' => FACEBOOK_LOGIN_APP_SECRET,
        ));

        // get user id (string)
        $user = $facebook->getUser();

        // if the user object (array?) exists, the user has identified as a real facebook user
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $facebook_user_data = $facebook->api('/me');

                // check database for data from exactly that user (identified via Facebook ID)
                $sth = $this->db->prepare("SELECT user_id,
                                              user_name,
                                              user_email,
                                              user_account_type,
                                              user_provider_type
                                       FROM   users
                                       WHERE  user_facebook_uid = :user_facebook_uid
                                              AND user_provider_type = :provider_type");
                $sth->execute(array(':user_facebook_uid' => $facebook_user_data["id"], ':provider_type' => 'FACEBOOK'));

                $count =  $sth->rowCount();
                if ($count == 1) {
                    // fetch one row (we only have one result)
                    // TODO: catch errors here
                    $result = $sth->fetch();

                    // put user data into session
                    Session::init();
                    Session::set('user_logged_in', true);
                    Session::set('user_id', $result->user_id);
                    Session::set('user_name', $result->user_name);
                    Session::set('user_email', $result->user_email);
                    Session::set('user_account_type', $result->user_account_type);
                    Session::set('user_provider_type', 'FACEBOOK');
                    Session::set('user_avatar_file', $this->getUserAvatarFilePath());

                    return true;
                } else {
                    $_SESSION["feedback_negative"][] = FEEDBACK_FACEBOOK_LOGIN_NOT_REGISTERED;
                }
            } catch (FacebookApiException $e) {
                // TODO: handle the catch results, when something goes wrong with FB login
                // when facebook goes offline
                error_log($e);
                $user = null;
            }
        }
        // default return
        return false;
    }

    /**
     * Gets the last page the user visited from the cookie
     * Useful for relocating (TODO: explain this better)
     * @return string view/location the user visited
     */
    public function getCookieUrl()
    {
        $url = '';
        if (!empty($_COOKIE['lastvisitedpage'])) {
            $url = $_COOKIE['lastvisitedpage'];
        }
        return $url;
    }
    
    /**
     * Log out process, deletes cookie, deletes session
     */
    public function logout()
    {
        // set the remember-me-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obviously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/');
        
        // delete the session
        Session::destroy();
    }

    /**
     * Deletes the (invalid) remember-cookie to prevent infinitive login loops
     */
    public function deleteCookie()
    {
        // set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obivously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/');
    }

    /**
     * Simply returns the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        return Session::get('user_logged_in');
    }        
        
    /**
     * Edit the user's name, provided in the editing form
     */
    public function editUserName()
    {
        if (!empty($_POST['user_name'])) {
        
            if (!empty($_POST['user_name']) && $_POST['user_name'] == $_SESSION["user_name"]) {
                $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_SAME_AS_OLD_ONE;
            }
            // username cannot be empty and must be azAZ09 and 2-64 characters
            elseif (!empty($_POST['user_name'])
                    AND preg_match("/^(?=.{2,64}$)[a-zA-Z][a-zA-Z0-9]*(?: [a-zA-Z0-9]+)*$/", $_POST['user_name'])) {

                // clean the input
                $this->user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
                $this->user_name = substr($this->user_name, 0, 64);

                // check if new username already exists
                $sth = $this->db->prepare("SELECT * FROM users WHERE user_name = :user_name ;");
                $sth->execute(array(':user_name' => $this->user_name));

                $count =  $sth->rowCount();

                if ($count == 1) {
                    $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_ALREADY_TAKEN;
                } else {
                    $sth = $this->db->prepare("UPDATE users SET user_name = :user_name WHERE user_id = :user_id ;");
                    $sth->execute(array(':user_name' => $this->user_name, ':user_id' => $_SESSION['user_id']));

                    $count =  $sth->rowCount();
                    if ($count == 1) {
                        Session::set('user_name', $this->user_name);
                        $_SESSION["feedback_positive"][] = FEEDBACK_USERNAME_CHANGE_SUCCESSFUL;
                    } else {
                        $_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
                    }
                }
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN;
            }
        } elseif (!empty($_POST['user_username'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_FIELD_EMPTY;
        } elseif (!empty($_POST['user_password'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_FIELD_EMPTY;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY;
        }
    }

    /**
     * Edit the user's email, provided in the editing form
     */
    public function editUserEmail()
    {
        if (!empty($_POST['user_email'])) {
            
            // check if new email is same like the old one
            if (!empty($_POST['user_email']) && $_POST['user_email'] == $_SESSION["user_email"]) {
                $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_SAME_AS_OLD_ONE;
            } 
            // user mail must be in email format
            elseif (filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                // check if user's email already exists
                $sth = $this->db->prepare("SELECT * FROM users WHERE user_email = :user_email");
                $sth->execute(array(':user_email' => $_POST['user_email']));

                $count =  $sth->rowCount();
                if ($count == 1) {
                    $_SESSION["feedback_negative"][] = FEEDBACK_USER_EMAIL_ALREADY_TAKEN;
                } else {
                    // cleaning
                    $this->user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
                    $this->user_email = substr($this->user_email, 0, 64);
                    // write new email to database
                    $sth = $this->db->prepare("UPDATE users SET user_email = :user_email WHERE user_id = :user_id ;");
                    $sth->execute(array(':user_email' => $this->user_email, ':user_id' => $_SESSION['user_id']));

                    $count =  $sth->rowCount();
                    // if successful
                    if ($count == 1) {
                        Session::set('user_email', $this->user_email);
                        // call the setGravatarImageUrl() method which writes gravatar urls into the session
                        $this->setGravatarImageUrl($this->user_email);

                        $_SESSION["feedback_positive"][] = FEEDBACK_EMAIL_CHANGE_SUCCESSFUL;
                    } else {
                        $_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
                    }
                }
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN;
            }
        } elseif (!empty($_POST['user_email'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_FIELD_EMPTY;
        }
    } 
    
    /**
     * handles the entire registration process for DEFAULT users (not for people who register with
     * 3rd party services, like facebook) and creates a new user in the database if everything is fine
     * TODO: total refactoring, get rid off if/else nesting
     * @return boolean Gives back the success status of the registration
     */
    public function registerNewUser()
    {
        // create new Captcha object
        $captcha = new Captcha();

        // perform all necessary form checks
        if (!$captcha->checkCaptcha()) {
            $_SESSION["feedback_negative"][] = FEEDBACK_CAPTCHA_WRONG;
        } elseif (empty($_POST['user_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_FIELD_EMPTY;
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_FIELD_EMPTY;
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_REPEAT_WRONG;
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_TOO_SHORT;
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN;
        } elseif (empty($_POST['user_email'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_FIELD_EMPTY;
        } elseif (strlen($_POST['user_email']) > 64) {
            $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_TOO_LONG;
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN;
        } elseif (!empty($_POST['user_name'])
                  && strlen($_POST['user_name']) <= 64
                  && strlen($_POST['user_name']) >= 2
                  && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
                  && !empty($_POST['user_email'])
                  && strlen($_POST['user_email']) <= 64
                  && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
                  && !empty($_POST['user_password_new']) 
                  && !empty($_POST['user_password_repeat']) 
                  && ($_POST['user_password_new'] === $_POST['user_password_repeat'])) {
            
                // escapin' this, additionally removing everything that could be (html/javascript-) code
                $this->user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
                $this->user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
                // no need to escape as this is only used in the hash function
                $this->user_password = $_POST['user_password_new'];
                // now it gets a little bit crazy: check if we have a constant HASH_COST_FACTOR defined (in config/hashing.php),
                // if so: put the value into $this->hash_cost_factor, if not, make $this->hash_cost_factor = null
                $this->hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
                // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
                // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
                // want the parameter: as an array with, currently only used with 'cost' => XX.
                $this->user_password_hash = password_hash($this->user_password, PASSWORD_DEFAULT, array('cost' => $this->hash_cost_factor));
                
                // check if user already exists                
                $sth = $this->db->prepare("SELECT * FROM users WHERE user_name = :user_name");
                $sth->execute(array(':user_name' => $this->user_name));
                
                $count =  $sth->rowCount();            

                if ($count == 1) {
                    $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_ALREADY_TAKEN;
                } else {

                    // check if user's email already exists
                    $sth = $this->db->prepare("SELECT user_id FROM users WHERE user_email = :user_email");
                    $sth->execute(array(':user_email' => $this->user_email));

                    $count =  $sth->rowCount();

                    if ($count == 1) {
                        $_SESSION["feedback_negative"][] = FEEDBACK_USER_EMAIL_ALREADY_TAKEN;
                    } else {
                        // generate random hash for email verification (40 char string)
                        $this->user_activation_hash = sha1(uniqid(mt_rand(), true));

                        // write new users data into database
                        $sql = "INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash, user_provider_type)
                                VALUES (:user_name, :user_password_hash, :user_email, :user_activation_hash, :user_provider_type)";
                        $sth = $this->db->prepare($sql);
                        $sth->execute(array(':user_name' => $this->user_name,
                                            ':user_password_hash' => $this->user_password_hash,
                                            ':user_email' => $this->user_email,
                                            ':user_activation_hash' => $this->user_activation_hash,
                                            ':user_provider_type' => 'DEFAULT'));

                        $count =  $sth->rowCount();
                        if ($count == 1) {

                            // get user_id of the user that has been created
                            // to keep things clean and professional we DON'T use lastInsertId() here
                            $sth = $this->db->prepare("SELECT user_id FROM users WHERE user_name = :user_name");
                            $sth->execute(array(':user_name' => $this->user_name));

                            if ($sth->rowCount() == 1) {

                                $result_user_row = $sth->fetch();
                                $this->user_id = $result_user_row->user_id;

                                // send a verification email
                                if ($this->sendVerificationEmail($this->user_id, $this->user_email, $this->user_activation_hash)) {
                                    // when mail has been send successfully
                                    $this->messages[] = FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED;
                                    $this->registration_successful = true;
                                    return true;
                                } else {
                                    // if verification email didn't sent, instantly delete the user
                                    $sth = $this->db->prepare("DELETE FROM users WHERE user_id = :last_inserted_id");
                                    $sth->execute(array(':last_inserted_id' => $this->user_id));
                                    $_SESSION["feedback_negative"][] = FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED;
                                }
                            } else {
                                $_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
                            }
                        } else {
                            $_SESSION["feedback_negative"][] = FEEDBACK_ACCOUNT_CREATION_FAILED;
                        }
                    }
                }
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
        }
        // standard return. returns only true of really successful (see above)
        return false;
    }

    /**
     * sends an email to the provided email address
     * @param $user_id int user's id
     * @param $user_email string user's email
     * @param $user_activation_hash string user's mail verification hash string
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    private function sendVerificationEmail($user_id, $user_email, $user_activation_hash)
    {
        // create PHPMailer object (this is easily possible as we auto-load the according class(es) via composer)
        $mail = new PHPMailer;

        // please look into the config/config.php for much more info on how to use this!
        if (EMAIL_USE_SMTP) {
            // set PHPMailer to use SMTP
            $mail->IsSMTP();
            // useful for debugging, shows full SMTP errors, config this in config/config.php
            $mail->SMTPDebug = PHPMAILER_DEBUG_MODE;
            // enable SMTP authentication
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;                               
            // enable encryption, usually SSL/TLS
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;                              
            }
            // set SMTP provider's credentials
            $mail->Host = EMAIL_SMTP_HOST;  
            $mail->Username = EMAIL_SMTP_USERNAME;                            
            $mail->Password = EMAIL_SMTP_PASSWORD;                      
            $mail->Port = EMAIL_SMTP_PORT;
        } else {
            $mail->IsMail();            
        }

        // fill mail with data
        $mail->From = EMAIL_VERIFICATION_FROM_EMAIL;
        $mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_VERIFICATION_SUBJECT;
        $mail->Body = EMAIL_VERIFICATION_CONTENT . EMAIL_VERIFICATION_URL.'/'.urlencode($user_id).'/'.urlencode($user_activation_hash);

        // final sending and check
        if($mail->Send()) {
            $_SESSION["feedback_positive"][] = FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL;
            return true;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR . $mail->ErrorInfo;
        }

        // default return
        return false;
    }
    
    /**
     * verifyNewUser()
     * checks the email/verification code combination and set the user's activation status to true (=1) in the database
     * @param $user_id
     * @param $user_verification_code
     */
    public function verifyNewUser($user_id, $user_verification_code)
    {
        $sth = $this->db->prepare("UPDATE users
                                   SET user_active = 1, user_activation_hash = NULL
                                   WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash");
        $sth->execute(array(':user_id' => $user_id, ':user_activation_hash' => $user_verification_code));                                  

        if ($sth->rowCount() > 0) {
            $_SESSION["feedback_positive"][] = FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_ACCOUNT_ACTIVATION_FAILED;
        }
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     * Gravatar is the #1 (free) provider for email address based global avatar hosting.
     * The URL (or image) returns always a .jpg file !
     * For deeper info on the different parameter possibilities:
     * @see http://gravatar.com/site/implement/images/
     *
     * @param string $email The email address
     * @param int|string $s Size in pixels, defaults to 50px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function setGravatarImageUrl($email, $s = 44, $d = 'mm', $r = 'pg', $atts = array() )
    {
        // TODO: why is this set when it's more a get ?
        // TODO this thing is messy
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        
        // the image url (on gravatar servers), will return in something like
        // http://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=80&d=mm&r=g
        // note: the url does NOT have something like .jpg
        Session::set('user_gravatar_image_url', $url);

        // build img tag around
        $url_with_tag = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val ) {
            $url_with_tag .= ' ' . $key . '="' . $val . '"';
        }
        $url_with_tag .= ' />';            
 
        // the image url like above but with an additional <img src .. /> around
        Session::set('user_gravatar_image_tag', $url_with_tag);
    }
    
    /**
     * Gets the user's avatar file path
     * @return string avatar picture path
     */
    public function getUserAvatarFilePath()
    {
        $sth = $this->db->prepare("SELECT user_has_avatar FROM users WHERE user_id = :user_id");
        $sth->execute(array(':user_id' => $_SESSION['user_id']));

        if ($sth->fetch()->user_has_avatar) {
            return URL . AVATAR_PATH . $_SESSION['user_id'] . '.jpg';
        }
    }

    /**
     *
     */
    public function createAvatar()
    {
        if (is_dir(AVATAR_PATH) && is_writable(AVATAR_PATH)) {
            if (!empty ($_FILES['avatar_file']['tmp_name'])) {
                // get the image width, height and mime type
                // btw: why does PHP call this getimagesize when it gets much more than just the size ?
                $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

                // don't handle files > 5MB
                if ($_FILES['avatar_file']['size'] <= 5000000 ) {
                    if ($image_proportions[0] >= 100 && $image_proportions[1] >= 100) {
                        if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') {

                            $target_file_path = AVATAR_PATH . $_SESSION['user_id'] . ".jpg";
                            // creates a 44x44px avatar jpg file in the avatar folder
                            // see the function defintion (also in this class) for more info on how to use
                            $this->resize_image($_FILES['avatar_file']['tmp_name'], $target_file_path, 44, 44, 85, true);
                            $sth = $this->db->prepare("UPDATE users SET user_has_avatar = TRUE WHERE user_id = :user_id");
                            $sth->execute(array(':user_id' => $_SESSION['user_id']));
                            Session::set('user_avatar_file', $this->getUserAvatarFilePath());
                            $_SESSION["feedback_positive"][] = FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL;
                        } else {
                            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE;
                        }
                    } else {
                        $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_UPLOAD_TOO_SMALL;
                    }
                } else {
                    $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_UPLOAD_TOO_BIG;
                } 
            }
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_AVATAR_FOLDER_NOT_WRITABLE;
        }
    }
    
    /**
     * Resize Image
     * TODO: uh, this looks dirty!
     *
     * Takes the source image and resizes it to the specified width & height or proportionally if crop is off.
     * @access public
     * @author Jay Zawrotny <jayzawrotny@gmail.com>
     * @license Do whatever you want with it.
     * @param string $source_image The location to the original raw image.
     * @param string $destination_filename The location to save the new image.
     * @param int $width The desired width of the new image
     * @param int $height The desired height of the new image.
     * @param int $quality The quality of the JPG to produce 1 - 100
     * @param bool $crop Whether to crop the image or not. It always crops from the center.
     * @return bool
     */
    function resize_image($source_image, $destination_filename, $width = 44, $height = 44, $quality = 85, $crop = true)
    {
        if ( ! $image_data = getimagesize( $source_image ) ) {
            return false;
        }

        switch( $image_data['mime'] ) {
            case 'image/gif':
                $get_func = 'imagecreatefromgif';
                $suffix = ".gif";
            break;
            case 'image/jpeg';
                $get_func = 'imagecreatefromjpeg';
                $suffix = ".jpg";
            break;
            case 'image/png':
                $get_func = 'imagecreatefrompng';
                $suffix = ".png";
            break;
        }

        $img_original = call_user_func( $get_func, $source_image );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round( $old_width / $old_height, 2 );
        $desired_ratio_after = round( $width / $height, 2 );
        $desired_ratio_before = round( $height / $width, 2 );

        if ( $old_width < $width || $old_height < $height ) {
             // The desired image size is bigger than the original image.
             // Best not to do anything at all really.
            return false;
        }

        // If the crop option is left on, it will take an image and best fit it
        // so it will always come out the exact specified size.
        if ( $crop ) {
            // create empty image of the specified size
            $new_image = imagecreatetruecolor( $width, $height );

            // Landscape Image
            if( $current_ratio > $desired_ratio_after ) {
                $new_width = $old_width * $height / $old_height;
            }

            // Nearly square ratio image.
            if ( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after ) {

                if ( $old_width > $old_height ) {
                    $new_height = max( $width, $height );
                    $new_width = $old_width * $new_height / $old_height;
                } else {
                    $new_height = $old_height * $width / $old_width;
                }
            }

            // Portrait sized image
            if ( $current_ratio < $desired_ratio_before  ) {
                $new_height = $old_height * $width / $old_width;
            }

            // Find out the ratio of the original photo to it's new, thumbnail-based size
            // for both the width and the height. It's used to find out where to crop.
            $width_ratio = $old_width / $new_width;
            $height_ratio = $old_height / $new_height;

            // Calculate where to crop based on the center of the image
            $src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
            $src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
        }
        // Don't crop the image, just resize it proportionally
        else {

            if ( $old_width > $old_height ) {
                $ratio = max( $old_width, $old_height ) / max( $width, $height );
            } else {
                $ratio = max( $old_width, $old_height ) / min( $width, $height );
            }

            $new_width = $old_width / $ratio;
            $new_height = $old_height / $ratio;
            $new_image = imagecreatetruecolor( $new_width, $new_height );
        }

        // Where all the real magic happens
        imagecopyresampled($new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height);

        // Save it as a JPG File with our $destination_filename param.
        imagejpeg( $new_image, $destination_filename, $quality  );

        // Destroy the evidence!
        imagedestroy( $new_image );
        imagedestroy( $img_original );

        // Return true because it worked and we're happy. Let the dancing commence!
        return true;
    }
    
    /**
     * Set password reset token in database (for DEFAULT user accounts)
     */
    public function setPasswordResetDatabaseToken()
    {
        if (empty($_POST['user_name'])) {
            $_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_FIELD_EMPTY;
        } else {
            // generate timestamp (to see when exactly the user (or an attacker) requested the password reset mail)
            // btw this is an integer ;)
            $temporary_timestamp = time();
            
            // generate random hash for email password reset verification (40 char string)
            $this->user_password_reset_hash = sha1(uniqid(mt_rand(), true));
                
            // TODO: this is not totally clean, as this is just the form provided username
            $this->user_name = htmlentities($_POST['user_name'], ENT_QUOTES);                
            
            $sth = $this->db->prepare("SELECT user_id, user_email
                                       FROM users
                                       WHERE user_name = :user_name
                                       AND user_provider_type = :provider_type");
            $sth->execute(array(':user_name' => $this->user_name, ':provider_type' => 'DEFAULT'));

            $count =  $sth->rowCount();
            if ($count == 1) {
                // get result row (as an object)
                $result_user_row = $result = $sth->fetch();
                // database query: 
                $sth2 = $this->db->prepare("UPDATE users 
                                            SET user_password_reset_hash = :user_password_reset_hash,
                                                user_password_reset_timestamp = :user_password_reset_timestamp
                                            WHERE user_name = :user_name AND user_provider_type = :provider_type");
                $sth2->execute(array(':user_password_reset_hash' => $this->user_password_reset_hash,
                                    ':user_password_reset_timestamp' => $temporary_timestamp,
                                    ':user_name' => $this->user_name,
                                    ':provider_type' => 'DEFAULT'));

                // check if exactly one row was successfully changed:
                $count =  $sth2->rowCount();
                if ($count == 1) {
                    // define email
                    $this->user_email = $result_user_row->user_email;
                    return true;
                } else {
                    $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_RESET_TOKEN_FAIL; // maybe say something not that technical.
                }
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_USER_DOES_NOT_EXIST;
            }
        }
        // return false (this method only returns true when the database entry has been set successfully)
        return false;        
    }

    /**
     * @return bool Has the password reset mail been sent successfully ?
     */
    public function sendPasswordResetMail()
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
            if (defined(EMAIL_SMTP_ENCRYPTION)) {                
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
        
        $mail->From = EMAIL_PASSWORDRESET_FROM_EMAIL;
        $mail->FromName = EMAIL_PASSWORDRESET_FROM_NAME;        
        $mail->AddAddress($this->user_email);
        $mail->Subject = EMAIL_PASSWORDRESET_SUBJECT;
        $link = EMAIL_PASSWORDRESET_URL.'/'.urlencode($this->user_name).'/'.urlencode($this->user_password_reset_hash);
        $mail->Body = EMAIL_PASSWORDRESET_CONTENT.' <a href="'.$link.'">'.$link.'</a>';

        if(!$mail->Send()) {
           $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR . $mail->ErrorInfo;
           return false;
        } else {
            $_SESSION["feedback_positive"][] = FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL;
            return true;
        }
    }    
    
    /**
     * TODO: why is this not camelCase ?
     * TODO: it's the password RESET request, but it's not in the name
     */
    public function verifypasswordrequest($user_name, $verification_code)
    {
        // TODO: this is not totally clean, as this is just the form provided username
        $this->user_name                = htmlentities($user_name, ENT_QUOTES);         
        $this->user_password_reset_hash = htmlentities($verification_code, ENT_QUOTES);    

        $sth = $this->db->prepare("SELECT user_id, user_password_reset_timestamp 
                                   FROM users 
                                   WHERE user_name = :user_name 
                                     AND user_password_reset_hash = :user_password_reset_hash
                                     AND user_provider_type = :user_provider_type");
        $sth->execute(array(':user_password_reset_hash' => $verification_code,
                            ':user_name' => $user_name,
                            ':user_provider_type' => 'DEFAULT'));

        // if this user exists
        if ($sth->rowCount() == 1) {
            // get result row (as an object)
            $result_user_row = $sth->fetch();
            // 3600 seconds are 1 hour
            $timestamp_one_hour_ago = time() - 3600;
            // if password reset request was sent within the last hour (this timeout is for security reasons)
            if ($result_user_row->user_password_reset_timestamp > $timestamp_one_hour_ago) {
                // verification was successful
                return true;
            } else {
                // password reset request is older than one hour, reject the request
                $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_RESET_LINK_EXPIRED;
                return false;
            }
        } else {
            // wrong verification code (=user_password_reset_hash) for this user
            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST;
            return false;
        }
    }

    /**
     * Set the new password (for DEFAULT user, FACEBOOK-users don't have a password)
     * @return bool
     */
    public function setNewPassword()
    {
        // TODO: timestamp!
        if (!empty($_POST['user_name'])
            && !empty($_POST['user_password_reset_hash'])
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])) {
                
            if ($_POST['user_password_new'] === $_POST['user_password_repeat']) {
                if (strlen($_POST['user_password_new']) >= 6) {

                        // escaping, additionally removing everything that could be (html/javascript-) code
                        $this->user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
                        $this->user_password_reset_hash = htmlentities($_POST['user_password_reset_hash'], ENT_QUOTES);
                        
                        // no need to escape as this is only used in the hash function
                        $this->user_password = $_POST['user_password_new'];

                        // now it gets a little bit crazy: check if we have a constant HASH_COST_FACTOR defined (in config/hashing.php),
                        // if so: put the value into $this->hash_cost_factor, if not, make $this->hash_cost_factor = null
                        $this->hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);

                        // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
                        // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
                        // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
                        // want the parameter: as an array with, currently only used with 'cost' => XX.
                        $this->user_password_hash = password_hash($this->user_password, PASSWORD_DEFAULT, array('cost' => $this->hash_cost_factor));

                        // write users new password hash into database
                        $sth = $this->db->prepare("UPDATE users
                                                   SET user_password_hash = :user_password_hash,
                                                       user_password_reset_hash = NULL,
                                                       user_password_reset_timestamp = NULL
                                                   WHERE user_name = :user_name
                                                     AND user_password_reset_hash = :user_password_reset_hash
                                                     AND user_provider_type = :user_provider_type");

                        $sth->execute(array(':user_password_hash' => $this->user_password_hash,
                                            ':user_name' => $this->user_name,
                                            ':user_password_reset_hash' => $this->user_password_reset_hash,
                                            ':user_provider_type' => 'DEFAULT'));
                        
                        // check if exactly one row was successfully changed:
                        if ($sth->rowCount() == 1) {
                            $_SESSION["feedback_positive"][] = FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL;
                            return true;
                        } else {
                            $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_CHANGE_FAILED;
                        }
                } else {
                    $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_TOO_SHORT;
                }
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_REPEAT_WRONG;
            }
        }
        // default
        return false;
    }

    /**
     * Upgrades/downgrades the user's account (for DEFAULT and FACEBOOK users)
     * Currently it's just the field user_account_type in the database that
     * can be 1 or 2 (maybe "basic" or "premium"). In this basic method we
     * simply increase or decrease this value to emulate an account upgrade/downgrade.
     * Put some more complex stuff in here, maybe a pay-process or whatever you like.
     */
    public function changeAccountType()
    {
        if (!empty($_POST["user_account_upgrade"])) {

            // do whatever you want to upgrade the account here (pay-process etc)
            // ...
            // ... myPayProcess();
            // ...

            // upgrade account type
            $sth = $this->db->prepare("UPDATE users SET user_account_type = 2 WHERE user_id = :user_id");
            $sth->execute(array(':user_id' => $_SESSION["user_id"]));                                  

            if ($sth->rowCount() == 1) {
                // set account type in session to 2
                Session::set('user_account_type', 2);
                $_SESSION["feedback_positive"][] = FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL;
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_ACCOUNT_UPGRADE_FAILED;
            }
        } elseif (!empty($_POST["user_account_downgrade"])) {

            // do whatever you want to downgrade the account here (pay-process etc)
            // ...
            // ... myWhateverProcess();
            // ...
            
            $sth = $this->db->prepare("UPDATE users SET user_account_type = 1 WHERE user_id = :user_id");
            $sth->execute(array(':user_id' => $_SESSION["user_id"]));
            
            if ($sth->rowCount() == 1) {
                // set account type in session to 1
                Session::set('user_account_type', 1);
                $_SESSION["feedback_positive"][] = FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL;
            } else {
                $_SESSION["feedback_negative"][] = FEEDBACK_ACCOUNT_DOWNGRADE_FAILED;
            }
        }
    }

    /**
     * Register user with data from the "facebook object"
     * @param $facebook_user_data
     * @return bool
     */
    public function registerNewUserWithFacebook($facebook_user_data)
    {
        // delete dots from facebook's username (it's the common way to do this like that)
        $clean_user_name_from_facebook = str_replace(".", "", $facebook_user_data["username"]);

        $sql = "INSERT INTO users (user_name, user_email, user_active, user_provider_type, user_facebook_uid)
                VALUES (:user_name, :user_email, :user_active, :user_provider_type, :user_facebook_uid)";
        $sth = $this->db->prepare($sql);
        $sth->execute(array(':user_name' => $clean_user_name_from_facebook,
                            ':user_email' => $facebook_user_data["email"],
                            ':user_active' => 1,
                            ':user_provider_type' => 'FACEBOOK',
                            ':user_facebook_uid' => $facebook_user_data["id"]));

        $count =  $sth->rowCount();
        if ($count == 1) {
            $sth = $this->db->prepare("SELECT user_id,
                                              user_name,
                                              user_email,
                                              user_account_type,
                                              user_provider_type
                                       FROM   users
                                       WHERE  user_name = :user_name
                                              AND user_provider_type = :provider_type");
            $sth->execute(array(':user_name' => $clean_user_name_from_facebook, ':provider_type' => 'FACEBOOK'));

            // fetch one row (we only have one result)
            // TODO: catch errors here
            $result = $sth->fetch();

            // put user data into session
            Session::init();
            Session::set('user_logged_in', true);
            Session::set('user_id', $result->user_id);
            Session::set('user_name', $result->user_name);
            Session::set('user_email', $result->user_email);
            Session::set('user_account_type', $result->user_account_type);
            Session::set('user_provider_type', 'FACEBOOK');
            Session::set('user_avatar_file', $this->getUserAvatarFilePath());

            return true;
        }
        // default return
        return false;
    }

    /**
     * Checks if the facebook user data array has an email. It's possible that users block this, so we don't have
     * an email an therefore cannot register this person (registration without email is impossible).
     * @param $facebook_user_data
     * @return bool
     */
    public function facebookUserHasEmail($facebook_user_data)
    {
        if (isset($facebook_user_data["email"]) && !empty($facebook_user_data["email"])) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Check if the facebook-user's UID (unique facebook ID) already exists in our database
     * @param $facebook_user_data
     * @return bool
     */
    public function facebookUserIdExistsAlreadyInDatabase($facebook_user_data)
    {
        $sth = $this->db->prepare("SELECT user_id FROM users WHERE user_facebook_uid = :user_facebook_uid");
        $sth->execute(array(':user_facebook_uid' => $facebook_user_data["id"]));

        if ($sth->rowCount() == 1) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Checks if the facebook-user's username is already in our database
     * Note: Facebook-usernames have dots, so we remove all dots.
     * @param $facebook_user_data
     * @return bool
     */
    public function facebookUserNameExistsAlreadyInDatabase($facebook_user_data)
    {
        // delete dots from facebook's username (it's the common way to do this like that)
        $clean_user_name_from_facebook = str_replace(".", "", $facebook_user_data["username"]);

        $sth = $this->db->prepare("SELECT user_id FROM users WHERE user_name = :clean_user_name_from_facebook");
        $sth->execute(array(':clean_user_name_from_facebook' => $clean_user_name_from_facebook));

        if ($sth->rowCount() == 1) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Checks if the facebook-user's email address is already in our database
     * @param $facebook_user_data
     * @return bool
     */
    public function facebookUserEmailExistsAlreadyInDatabase($facebook_user_data)
    {
        $sth = $this->db->prepare("SELECT user_id FROM users WHERE user_email = :facebook_email");
        $sth->execute(array(':facebook_email' => $facebook_user_data["email"]));

        if ($sth->rowCount() == 1) {
            return true;
        }
        // default return
        return false;
    }
}
