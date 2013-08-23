<?php

/**
 * class Login
 * handles the user login/logout/session
 * 
 * @author Panique <panique@web.de>
 */
class Login
{
    /** @var object $db_connection The database connection */
    private $db_connection = null;
    /** @var int $hash_cost_factor The (optional) cost factor for the hash calculation */
    private $hash_cost_factor = null;

    /** @var int $user_id The user's id */
    private $user_id = null;
    /** @var string $user_name The user's name */
    private $user_name = "";
    /** @var string $user_email The user's mail */
    private $user_email = "";
    /** @var string $user_password_hash The user's hashed and salted password */
    private $user_password_hash = "";
    /** @var boolean $user_is_logged_in The user's login status */
    private $user_is_logged_in = false;
    /** @var string $user_password_reset_hash The user's password reset hash */
    private $user_password_reset_hash = "";
    /** @var string $user_gravatar_image_url The user's gravatar profile pic url (or a default one) */
    public $user_gravatar_image_url = "";
    /** @var string $user_gravatar_image_tag The user's gravatar profile pic url with <img ... /> around */
    public $user_gravatar_image_tag = "";

    /** @var boolean $password_reset_link_is_valid Marker for view handling */
    private $password_reset_link_is_valid  = false;
    /** @var boolean $password_reset_was_successful Marker for view handling */
    private $password_reset_was_successful = false;

    /** @var array $errors Collection of error messages */
    public $errors = array();
    /** @var array $messages Collection of success / neutral messages */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct()
    {
        // create/read session
        session_start();                                        

        // check the possible login actions:
        // 1. logout (happen when user clicks logout button)
        // 2. login via session data (happens each time user opens a page on your php project AFTER he has successfully logged in via the login form)
        // 3. login via cookie
        // 4. login via post data, which means simply logging in via the login form. after the user has submit his login/password successfully, his
        //    logged-in-status is written into his session data on the server. this is the typical behaviour of common login scripts.

        // if user tried to log out
        if (isset($_GET["logout"])) {

            $this->doLogout();

        // if user has an active session on the server
        } elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {

            $this->loginWithSessionData();

            // checking for form submit from editing screen
            if (isset($_POST["user_edit_submit_name"])) {

                $this->editUserName();

            } elseif (isset($_POST["user_edit_submit_email"])) {

                $this->editUserEmail();

            } elseif (isset($_POST["user_edit_submit_password"])) {

                $this->editUserPassword();

            }

        // login with cookie
        } elseif (isset($_COOKIE['rememberme'])) {

            $this->loginWithCookieData();

        // if user just submitted a login form
        } elseif (isset($_POST["login"])) {

            $this->loginWithPostData();

        }

        // checking if user requested a password reset mail
        if (isset($_POST["request_password_reset"])) {

            $this->setPasswordResetDatabaseTokenAndSendMail(); // maybe a little bit cheesy

        } elseif (isset($_GET["user_name"]) && isset($_GET["verification_code"])) {

            $this->checkIfEmailVerificationCodeIsValid();

        } elseif (isset($_POST["submit_new_password"])) {

            $this->editNewPassword();

        }

        // get gravatar profile picture if user is logged in
        if ($this->isUserLoggedIn() == true) {
            $this->getGravatarImageUrl($this->user_email);
        }

    }

    private function databaseConnection()
    {
        // connection already opened
        if ($this->db_connection != null) {
            return true;
        } else {
            // create a database connection, using the constants from config/config.php
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME, DB_USER, DB_PASS);
                return true;

                // If an error is catched, database connection failed
            } catch (PDOException $e) {
                $this->errors[] = "Database connection problem.";
                return false;
            }
        }
    }

    private function loginWithSessionData()
    {
        $this->user_name = $_SESSION['user_name'];
        $this->user_email = $_SESSION['user_email'];

        // set logged in status to true, because we just checked for this:
        // !empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)
        // when we called this method (in the constructor)
        $this->user_is_logged_in = true;        
    }

    private function loginWithCookieData()
    {
        if (isset($_COOKIE['rememberme'])) {

            list ($user_id, $token, $hash) = explode(':', $_COOKIE['rememberme']);

            if ($hash == hash('sha256', $user_id . ':' . $token . COOKIE_SECRET_KEY) && !empty($token)) {

                if ($this->databaseConnection()) {

                    // get real token from database (and all other data)
                    $sth = $this->db_connection->prepare("SELECT user_id, user_name, user_email FROM users WHERE user_id = :user_id
                                                      AND user_rememberme_token = :user_rememberme_token AND user_rememberme_token IS NOT NULL");
                    $sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    $sth->bindValue(':user_rememberme_token', $token, PDO::PARAM_STR);
                    $sth->execute();
                    // get result row (as an object)
                    $result_row = $sth->fetchObject();

                    if (isset($result_row->user_id)) {

                        // write user data into PHP SESSION [a file on your server]
                        $_SESSION['user_id'] = $result_row->user_id;
                        $_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_logged_in'] = 1;

                        // declare user id, set the login status to true
                        $this->user_id = $result_row->user_id;
                        $this->user_name = $result_row->user_name;
                        $this->user_email = $result_row->user_email;
                        $this->user_is_logged_in = true;

                        // Cookie token usable only once
                        $this->newRememberMeCookie();
                        return true;
                    }
                }
            }

            // A cookie has been used but is not valid... we delete it
            $this->deleteRememberMeCookie();
            $this->errors[] = "Invalid cookie";
        }
        return false;
    }

    private function loginWithPostData()
    {
        // if POST data (from login form) contains non-empty user_name and non-empty user_password
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            // if database connection opened
            if ($this->databaseConnection()) {

                // database query, getting all the info of the selected user
                $checklogin = $this->db_connection->prepare('SELECT user_id, user_name, user_email, user_password_hash, user_active FROM users WHERE user_name = :user_name');
                $checklogin->bindValue(':user_name', trim($_POST['user_name']), PDO::PARAM_STR);
                $checklogin->execute();
                // get result row (as an object)
                $result_row = $checklogin->fetchObject();

                // if this user exists
                if (isset($result_row->user_id)) {

                    // using PHP 5.5's password_verify() function to check if the provided passwords fits to the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {

                        if ($result_row->user_active == 1) {

                            // write user data into PHP SESSION [a file on your server]
                            $_SESSION['user_id'] = $result_row->user_id;
                            $_SESSION['user_name'] = $result_row->user_name;
                            $_SESSION['user_email'] = $result_row->user_email;
                            $_SESSION['user_logged_in'] = 1;

                            // declare user id, set the login status to true
                            $this->user_id = $result_row->user_id;
                            $this->user_name = $result_row->user_name;
                            $this->user_email = $result_row->user_email;
                            $this->user_is_logged_in = true;

                            // if user has check the "remember me" checkbox, then generate token and write cookie
                            if (isset($_POST['user_rememberme'])) {

                                $this->newRememberMeCookie();

                            } else {

                                // Reset rememberme token
                                $this->deleteRememberMeCookie();

                            }

                            // OPTIONAL: recalculate the user's password hash
                            // DELETE this if-block if you like, it only exists to recalculate users's hashes when you provide a cost factor,
                            // by default the script will use a cost factor of 10 and never change it.
                            // check if the have defined a cost factor in config/hashing.php
                            if (defined('HASH_COST_FACTOR')) {

                                // check if the hash needs to be rehashed
                                if (password_needs_rehash($result_row->user_password_hash, PASSWORD_DEFAULT, array('cost' => HASH_COST_FACTOR))) {

                                    // calculate new hash with new cost factor
                                    $this->user_password_hash = password_hash($_POST['user_password'], PASSWORD_DEFAULT, array('cost' => HASH_COST_FACTOR));

                                    // TODO: this should be put into another method !?
                                    $query_update = $this->db_connection->prepare('UPDATE users SET user_password_hash = :user_password_hash WHERE user_id = :user_id');
                                    $query_update->bindValue(':user_password_hash', $this->user_password_hash, PDO::PARAM_STR);
                                    $query_update->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
                                    $query_update->execute();

                                    if ($query_update->rowCount() == 0) {

                                        // writing new hash was successful. you should now output this to the user ;)

                                    } else {

                                        // writing new hash was NOT successful. you should now output this to the user ;)

                                    }

                                }

                            }

                            // TO CLARIFY: in future versions of the script: should we rehash every hash with standard cost factor
                            // when the HASH_COST_FACTOR in config/hashing.php is commented out ?                            

                        } else {

                            $this->errors[] = "Your account is not activated yet. Please click on the confirm link in the mail.";

                        }

                    } else {

                        $this->errors[] = "Wrong password. Try again.";

                    }                

                } else {

                    $this->errors[] = "This user does not exist.";
                }

            }

        } elseif (empty($_POST['user_name'])) {

            $this->errors[] = "Username field was empty.";

        } elseif (empty($_POST['user_password'])) {

            $this->errors[] = "Password field was empty.";
        }

    }

    private function newRememberMeCookie()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // generate 64 char random string and store it in current user data
            $random_token_string = hash('sha256', mt_rand());
            $sth = $this->db_connection->prepare("UPDATE users SET user_rememberme_token = :user_rememberme_token WHERE user_id = :user_id");
            $sth->execute(array(':user_rememberme_token' => $random_token_string, ':user_id' => $_SESSION['user_id']));

            // generate cookie string that consists of userid, randomstring and combined hash of both
            $cookie_string_first_part = $_SESSION['user_id'] . ':' . $random_token_string;
            $cookie_string_hash = hash('sha256', $cookie_string_first_part . COOKIE_SECRET_KEY);
            $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

            // set cookie
            setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME, "/", COOKIE_DOMAIN);
        }
    }

    private function deleteRememberMeCookie()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // Reset rememberme token
            $sth = $this->db_connection->prepare("UPDATE users SET user_rememberme_token = NULL WHERE user_id = :user_id");
            $sth->execute(array(':user_id' => $_SESSION['user_id']));
        }

        // set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obivously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        $this->deleteRememberMeCookie();

        $_SESSION = array();
        session_destroy();

        $this->user_is_logged_in = false;
        $this->messages[] = "You have been logged out.";
    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        return $this->user_is_logged_in;
    }
    
    /**
     * edit the user's name, provided in the editing form
     */
    public function editUserName()
    {
        if (!empty($_POST['user_name']) && $_POST['user_name'] == $_SESSION["user_name"]) {

            $this->errors[] = "Sorry, that username is the same as your current one. Please choose another one.";

        // username cannot be empty and must be azAZ09 and 2-64 characters
        // TODO: maybe this pattern should also be implemented in Registration.php (or other way round)
        } elseif (!empty($_POST['user_name']) && preg_match("/^(?=.{2,64}$)[a-zA-Z][a-zA-Z0-9]*(?: [a-zA-Z0-9]+)*$/", $_POST['user_name'])) {

            // if database connection opened
            if ($this->databaseConnection()) {

                // escapin' this
                $this->user_name = substr(trim($_POST['user_name']), 0, 64);
                $this->user_id = intval($_SESSION['user_id']);

                // check if new username already exists
                $query_check_user_name = $this->db_connection->prepare('SELECT user_id FROM users WHERE user_name = :user_name');
                $query_check_user_name->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                $query_check_user_name->execute();
                // get result row (as an object)
                $result_row = $query_check_user_name->fetchObject();

                if (isset($result_row->user_id)) {

                    $this->errors[] = "Sorry, that username is already taken. Please choose another one.";

                } else {

                    // write user's new data into database
                    $query_edit_user_name = $this->db_connection->prepare('UPDATE users SET user_name = :user_name WHERE user_id = :user_id');
                    $query_edit_user_name->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                    $query_edit_user_name->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
                    $query_edit_user_name->execute();

                    if ($query_edit_user_name->rowCount()) {

                        $_SESSION['user_name'] = $this->user_name;
                        $this->messages[] = "Your username has been changed successfully. New username is " . $this->user_name . ".";

                    } else {

                        $this->errors[] = "Sorry, your chosen username renaming failed.";

                    }

                }

            }

        } else {

            $this->errors[] = "Sorry, your chosen username does not fit into the naming pattern.";

        }

    }

    /**
     * edit the user's email, provided in the editing form
     */
    public function editUserEmail()
    {
        if (!empty($_POST['user_email']) && $_POST['user_email'] == $_SESSION["user_email"]) {

            $this->errors[] = "Sorry, that email address is the same as your current one. Please choose another one.";

        // user mail cannot be empty and must be in email format
        } elseif (!empty($_POST['user_email']) && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {

            // if database connection opened
            if ($this->databaseConnection()) {

                // prevent database flooding
                $this->user_email = substr(trim($_POST['user_email']), 0, 64); 
                // not really necessary, but just in case...
                $this->user_id = intval($_SESSION['user_id']);

                // write users new data into database
                $query_edit_user_email = $this->db_connection->prepare('UPDATE users SET user_email = :user_email WHERE user_id = :user_id');
                $query_edit_user_email->bindValue(':user_email', $this->user_email, PDO::PARAM_STR);
                $query_edit_user_email->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
                $query_edit_user_email->execute();

                if ($query_edit_user_email->rowCount()) {

                    $_SESSION['user_email'] = $this->user_email;
                    $this->messages[] = "Your email address has been changed successfully. New email address is " . $this->user_email . ".";

                } else {

                    $this->errors[] = "Sorry, your email changing failed.";

                }

            }

        } else {

            $this->errors[] = "Sorry, your chosen email does not fit into the naming pattern.";

        }

    }  

    /**
     * edit the user's password, provided in the editing form
     */
    public function editUserPassword()
    {
        if (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat']) || empty($_POST['user_password_old'])) {

            $this->errors[] = "Empty Password";            

        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {

            $this->errors[] = "Password and password repeat are not the same";   

        } elseif (strlen($_POST['user_password_new']) < 6) {

            $this->errors[] = "Password has a minimum length of 6 characters";            

        // all the above tests are ok
        } else {

            // if database connection opened
            if ($this->databaseConnection()) {

                // database query, getting hash of currently logged in user (to check with just provided password)
                $check_for_right_password = $this->db_connection->prepare('SELECT user_password_hash FROM users WHERE user_id = :user_id');
                $check_for_right_password->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $check_for_right_password->execute();
                // get result row (as an object)
                $result_row = $check_for_right_password->fetchObject();

                // if this user exists
                if (isset($result_row->user_password_hash)) {

                    // using PHP 5.5's password_verify() function to check if the provided passwords fits to the hash of that user's password
                    if (password_verify($_POST['user_password_old'], $result_row->user_password_hash)) {

                        // now it gets a little bit crazy: check if we have a constant HASH_COST_FACTOR defined (in config/hashing.php),
                        // if so: put the value into $this->hash_cost_factor, if not, make $this->hash_cost_factor = null
                        $this->hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);

                        // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
                        // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
                        // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
                        // want the parameter: as an array with, currently only used with 'cost' => XX.
                        $this->user_password_hash = password_hash($_POST['user_password_new'], PASSWORD_DEFAULT, array('cost' => $this->hash_cost_factor));                        

                        // write users new hash into database
                        $query_update = $this->db_connection->prepare('UPDATE users SET user_password_hash = :user_password_hash WHERE user_id = :user_id');
                        $query_update->bindValue(':user_password_hash', $this->user_password_hash, PDO::PARAM_STR);
                        $query_update->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                        $query_update->execute();

                        // check if exactly one row was successfully changed:
                        if ($query_update->rowCount()) {

                            $this->messages[] = "Password sucessfully changed!";

                        } else {

                            $this->errors[] = "Sorry, your password changing failed.";

                        }

                    } else {

                        $this->errors[] = "Your OLD password was wrong.";

                    }

                } else {

                    $this->errors[] = "This user does not exist.";
                }

            }

        }

    }   
    
    /**
     * 
     */
    public function setPasswordResetDatabaseTokenAndSendMail()
    {
        // set token (= a random hash string and a timestamp) into database, to see that THIS user really requested a password reset
        if ($this->setPasswordResetDatabaseToken() == true) {
            // send a mail to the user, containing a link with that token hash string
            $this->sendPasswordResetMail();
        }
    }
    
    /**
     * 
     */
    public function setPasswordResetDatabaseToken()
    {
        if (empty($_POST['user_name'])) {

            $this->errors[] = "Empty username";

        } else {

            // generate timestamp (to see when exactly the user (or an attacker) requested the password reset mail)
            // btw this is an integer ;)
            $temporary_timestamp = time();

            // generate random hash for email password reset verification (40 char string)
            $this->user_password_reset_hash = sha1(uniqid(mt_rand(), true));

            // if database connection opened
            if ($this->databaseConnection()) {

                // TODO: this is not totally clean, as this is just the form provided username
                $this->user_name = trim($_POST['user_name']); //$this->db_connection->real_escape_string(htmlentities($_POST['user_name'], ENT_QUOTES));                
                $query_get_user_data = $this->db_connection->prepare('SELECT user_id, user_email FROM users WHERE user_name = :user_name');
                $query_get_user_data->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                $query_get_user_data->execute();
                // get result row (as an object)
                $result_row = $query_get_user_data->fetchObject();

                // if this user exists
                if (isset($result_row->user_id)) {

                    // database query: 
                    $query_update = $this->db_connection->prepare('UPDATE users SET user_password_reset_hash = :user_password_reset_hash,
                                                                   user_password_reset_timestamp = :user_password_reset_timestamp
                                                                   WHERE user_name = :user_name');
                    $query_update->bindValue(':user_password_reset_hash', $this->user_password_reset_hash, PDO::PARAM_STR);
                    $query_update->bindValue(':user_password_reset_timestamp', $temporary_timestamp, PDO::PARAM_INT);
                    $query_update->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                    $query_update->execute();

                    // check if exactly one row was successfully changed:
                    if ($query_update->rowCount() == 1) {

                        // define email
                        $this->user_email = $result_row->user_email;

                        return true;

                    } else {

                        $this->errors[] = "Could not write token to database."; // maybe say something not that technical.

                    }

                } else {

                    $this->errors[] = "This username does not exist.";

                }

            }

        }

        // return false (this method only returns true when the database entry has been set successfully)
        return false;        
    }
    
    /**
     * 
     */
    public function sendPasswordResetMail()
    {
        $mail = new PHPMailer;

        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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

        $mail->From = EMAIL_PASSWORDRESET_FROM;
        $mail->FromName = EMAIL_PASSWORDRESET_FROM_NAME;        
        $mail->AddAddress($this->user_email);
        $mail->Subject = EMAIL_PASSWORDRESET_SUBJECT;

        $link    = EMAIL_PASSWORDRESET_URL.'?user_name='.urlencode($this->user_name).'&verification_code='.urlencode($this->user_password_reset_hash);
        $mail->Body = EMAIL_PASSWORDRESET_CONTENT.' <a href="'.$link.'">'.$link.'</a>';

        if(!$mail->Send()) {

            $this->errors[] = "Password reset mail NOT successfully sent! Error: " . $mail->ErrorInfo;
            return false;

        } else {

            $this->messages[] = "Password reset mail successfully sent!";
            return true;
        }

    }
    
    /**
     * 
     */
    public function checkIfEmailVerificationCodeIsValid()
    {
        if (!empty($_GET["user_name"]) && !empty($_GET["verification_code"])) {

            // if database connection opened
            if ($this->databaseConnection()) {

                // TODO: this is not totally clean, as this is just the form provided username
                $this->user_name                = trim($_GET['user_name']);
                $this->user_password_reset_hash = $_GET['verification_code'];

                $query_get_user_data = $this->db_connection->prepare('SELECT user_id, user_password_reset_timestamp FROM users 
                WHERE user_name = :user_name AND user_password_reset_hash = :user_password_reset_hash');
                $query_get_user_data->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                $query_get_user_data->bindValue(':user_password_reset_hash', $this->user_password_reset_hash, PDO::PARAM_STR);
                $query_get_user_data->execute();
                // get result row (as an object)
                $result_row = $query_get_user_data->fetchObject();

                // if this user exists
                if (isset($result_row->user_id)) {

                    $timestamp_one_hour_ago = time() - 3600; // 3600 seconds are 1 hour

                    if ($result_row->user_password_reset_timestamp > $timestamp_one_hour_ago) {

                        // set the marker to true, making it possible to show the password reset edit form view
                        $this->password_reset_link_is_valid = true;

                    } else {

                        $this->errors[] = "Your reset link has expired. Please use the reset link within one hour.";

                    }

                } else {

                    $this->errors[] = "This username does not exist.";

                }

            }

        } else {

            $this->errors[] = "Empty link parameter data.";

        }

    }
    
    /**
     * 
     */
    public function editNewPassword()
    {
        // TODO: timestamp!

        if (!empty($_POST['user_name'])
            && !empty($_POST['user_password_reset_hash'])
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])) {

            if ($_POST['user_password_new'] === $_POST['user_password_repeat']) {

                if (strlen($_POST['user_password_new']) >= 6) {

                    // if database connection opened
                    if ($this->databaseConnection()) {

                        // escapin' this, additionally removing everything that could be (html/javascript-) code
                        $this->user_name                = trim($_POST['user_name']);
                        $this->user_password_reset_hash = $_POST['user_password_reset_hash'];

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

                        // write users new hash into database
                        $query_update = $this->db_connection->prepare('UPDATE users SET user_password_hash = :user_password_hash, 
                                                                      user_password_reset_hash = NULL, user_password_reset_timestamp = NULL
                                                                      WHERE user_name = :user_name AND user_password_reset_hash = :user_password_reset_hash');
                        $query_update->bindValue(':user_password_hash', $this->user_password_hash, PDO::PARAM_STR);
                        $query_update->bindValue(':user_password_reset_hash', $this->user_password_reset_hash, PDO::PARAM_STR);
                        $query_update->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                        $query_update->execute();

                        // check if exactly one row was successfully changed:
                        if ($query_update->rowCount() == 1) {

                            $this->password_reset_was_successful = true;
                            $this->messages[] = "Password sucessfully changed!";

                        } else {

                            $this->errors[] = "Sorry, your password changing failed.";

                        }

                    }

                } else {

                    $this->errors[] = "Password too short, please request a new password reset.";

                }

            } else {

                $this->errors[] = "Passwords dont match, please request a new password reset.";

            }

        }

    }
    
    /**
     * 
     * @return boolean
     */
    public function passwordResetLinkIsValid()
    {
        return $this->password_reset_link_is_valid;
    }

    /**
     * 
     * @return boolean
     */
    public function passwordResetWasSuccessful()
    {
        return $this->password_reset_was_successful;
    }
    
    /**
     * 
     */
    public function getUsername()
    {
        return $this->user_name;
    }

    /**
     * 
     */
    public function getPasswordResetHash()
    {
        return $this->user_password_reset_hash;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     * Gravatar is the #1 (free) provider for email address based global avatar hosting.
     * The URL (or image) returns always a .jpg file !
     * For deeper info on the different parameter possibilities:
     * @see http://de.gravatar.com/site/implement/images/
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 50px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function getGravatarImageUrl($email, $s = 50, $d = 'mm', $r = 'g', $atts = array() )
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r&f=y";

        // the image url (on gravatarr servers), will return in something like
        // http://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=80&d=mm&r=g
        // note: the url does NOT have something like .jpg
        $this->user_gravatar_image_url = $url;

        // build img tag around
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';            
 
        // the image url like above but with an additional <img src .. /> around
        $this->user_gravatar_image_tag = $url;
    }

}
