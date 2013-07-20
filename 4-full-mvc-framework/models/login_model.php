<?php

/**
 * class Login_Model
 * handles the user's login, logout, username editing, password changing...
 * 
 * @author Panique <panique@web.de>
 */
class Login_Model extends Model
{
    public $errors = array();

    public function __construct() {
        
            parent::__construct();
            
    }

    public function login() {
        
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            $sth = $this->db->prepare("SELECT user_id, user_name, user_email, user_password_hash, user_active 
                                       FROM users
                                       WHERE user_name = :user_name ;");
            $sth->execute(array(':user_name' => $_POST['user_name']));

            $count =  $sth->rowCount();
            if ($count == 1) {

                    // fetch one row (we only have one result)
                    $result = $sth->fetch();

                    if (password_verify($_POST['user_password'], $result->user_password_hash)) {

                        if ($result->user_active == 1) {

                            // login
                            Session::init();
                            Session::set('user_logged_in', true);
                            Session::set('user_id', $result->user_id);
                            Session::set('user_name', $result->user_name);
                            Session::set('user_email', $result->user_email);
                            
                            // call the setGravatarImageUrl() method which writes gravatar urls into the session
                            $this->setGravatarImageUrl($result->user_email);
                            
                            //header('location: ../dashboard');
                            return true;                                

                        } else {

                            $this->errors[] = "Your account is not activated yet. Please click on the confirm link in the mail.";
                            return false;

                        }   


                    } else {

                        $this->errors[] = "Password was wrong.";
                        return false;
                    }

            } else {
                    $this->errors[] = "This user does not exists.";
                    return false;
            }

            //if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {


            //$data = $sth->fetchAll();

        } elseif (empty($_POST['user_name'])) {

            $this->errors[] = "Username field was empty.";

        } elseif (empty($_POST['user_password'])) {

            $this->errors[] = "Password field was empty.";
        }

    }
	
    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn() {
        
        return Session::get('user_logged_in');
        
    }        
        
    /**
     * edit the user's name, provided in the editing form
     */
    public function editUserName() {
        
        if (!empty($_POST['user_name']) && $_POST['user_name'] == $_SESSION["user_name"]) {
            
            $this->errors[] = "Sorry, that username is the same as your current one. Please choose another one.";
        
        } 
        // username cannot be empty and must be azAZ09 and 2-64 characters
        // TODO: maybe this pattern should also be implemented in Registration.php (or other way round)
        elseif (!empty($_POST['user_name']) && preg_match("/^(?=.{2,64}$)[a-zA-Z][a-zA-Z0-9]*(?: [a-zA-Z0-9]+)*$/", $_POST['user_name'])) {
            
            // escapin' this
            $this->user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
            $this->user_name = substr($this->user_name, 0, 64); // TODO: is this really necessary ?
            $this->user_id = $_SESSION['user_id']; // TODO: is this really necessary ?

            // check if new username already exists
            $sth = $this->db->prepare("SELECT * FROM users WHERE user_name = :user_name ;");
            $sth->execute(array(':user_name' => $this->user_name));

            $count =  $sth->rowCount();
            
            if ($count == 1) {

                $this->errors[] = "Sorry, that username is already taken. Please choose another one.";

            } else {

                $sth = $this->db->prepare("UPDATE users SET user_name = :user_name WHERE user_id = :user_id ;");
                $sth->execute(array(':user_name' => $this->user_name, ':user_id' => $this->user_id));                

                $count =  $sth->rowCount();

                if ($count == 1) {

                    Session::set('user_name', $this->user_name);
                    $this->errors[] = "Your username has been changed successfully. New username is " . $this->user_name . ".";

                } else {

                    $this->errors[] = "Sorry, your chosen username renaming failed.";

                }

            }
            
        } else {
            
            $this->errors[] = "Sorry, your chosen username does not fit into the naming pattern.";
            
        }
        
    }
    
    /**
     * edit the user's email, provided in the editing form
     */
    public function editUserEmail() {
        
        
        if (!empty($_POST['user_email']) && $_POST['user_email'] == $_SESSION["user_email"]) {
            
            $this->errors[] = "Sorry, that email address is the same as your current one. Please choose another one.";
        
        } 
        // user mail cannot be empty and must be in email format
        elseif (!empty($_POST['user_email']) && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                
            // escapin' this
            $this->user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
            // prevent database flooding
            $this->user_email = substr($this->user_email, 0, 64);
            // not really necessary, but just in case...
            $this->user_id = $_SESSION['user_id'];

            $sth = $this->db->prepare("UPDATE users SET user_email = :user_email WHERE user_id = :user_id ;");
            $sth->execute(array(':user_email' => $this->user_email, ':user_id' => $this->user_id));                        
            
            $count =  $sth->rowCount();
            
            if ($count == 1) {

                Session::set('user_email', $this->user_email);
                
                // call the setGravatarImageUrl() method which writes gravatar urls into the session
                $this->setGravatarImageUrl($this->user_email);                
                
                $this->errors[] = "Your email address has been changed successfully. New email address is " . $this->user_email . ".";

            } else {

                $this->errors[] = "Sorry, your email changing failed.";

            }
            
        } else {
            
            $this->errors[] = "Sorry, your chosen email does not fit into the naming pattern.";
            
        }        
        
    } 
    
    /**
     * registerNewUser()
     * 
     * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
     * everything is fine
     * @return boolean Gives back the success status of the registration
     */
    public function registerNewUser() {
        
        $captcha = new Captcha();
        
        if (!$captcha->checkCaptcha()) {
        
            $this->errors[] = "The entered captcha security characters were wrong!";
            
        } elseif (empty($_POST['user_name'])) {
          
            $this->errors[] = "Empty Username";

        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
          
            $this->errors[] = "Empty Password";            
            
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
          
            $this->errors[] = "Password and password repeat are not the same";   
            
        } elseif (strlen($_POST['user_password_new']) < 6) {
            
            $this->errors[] = "Password has a minimum length of 6 characters";            
                        
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            
            $this->errors[] = "Username cannot be shorter than 2 or longer than 64 characters";
                        
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            
            $this->errors[] = "Username does not fit the name sheme: only a-Z and numbers are allowed, 2 to 64 characters";
            
        } elseif (empty($_POST['user_email'])) {
            
            $this->errors[] = "Email cannot be empty";
            
        } elseif (strlen($_POST['user_email']) > 64) {
            
            $this->errors[] = "Email cannot be longer than 64 characters";
            
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            
            $this->errors[] = "Your email address is not in a valid email format";
        
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
                $sth = $this->db->prepare("SELECT * FROM users WHERE user_name = :user_name ;");
                $sth->execute(array(':user_name' => $this->user_name));
                
                $count =  $sth->rowCount();            

                if ($count == 1) {

                    $this->errors[] = "Sorry, that username is already taken. Please choose another one.";

                } else {
                    
                    // generate random hash for email verification (40 char string)
                    $this->user_activation_hash = sha1(uniqid(mt_rand(), true));

                    // write new users data into database
                    //$query_new_user_insert = $this->db_connection->query("INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash) VALUES('".$this->user_name."', '".$this->user_password_hash."', '".$this->user_email."', '".$this->user_activation_hash."');");
                    
                    $sth = $this->db->prepare("INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash) VALUES(:user_name, :user_password_hash, :user_email, :user_activation_hash) ;");
                    $sth->execute(array(':user_name' => $this->user_name, ':user_password_hash' => $this->user_password_hash, ':user_email' => $this->user_email, ':user_activation_hash' => $this->user_activation_hash));                    
                    
                    $count =  $sth->rowCount();

                    if ($count == 1) {
                        
                        $this->user_id = $this->db->lastInsertId();                      
                        
                        // send a verification email
                        if ($this->sendVerificationEmail()) {
                            
                            // when mail has been send successfully
                            $this->messages[] = "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.";
                            $this->registration_successful = true;
                            return true;
                            
                        } else {

                            // delete this users account immediately, as we could not send a verification email
                            // the row (which will be deleted) is identified by PDO's lastinserid method (= the last inserted row)
                            // @see http://www.php.net/manual/en/pdo.lastinsertid.php
                            
                            $sth = $this->db->prepare("DELETE FROM users WHERE user_id = :last_inserted_id ;");
                            $sth->execute(array(':last_inserted_id' => $this->db->lastInsertId() ));
                            
                            
                            $this->errors[] = "Sorry, we could not send you an verification mail. Your account has NOT been created.";

                        }

                    } else {

                        $this->errors[] = "Sorry, your registration failed. Please go back and try again.";

                    }
                }            
            
        } else {
            
            $this->errors[] = "An unknown error occured.";
            
        }          
        
        // standard return. returns only true of really successful (see above)
        return false;
    }
    
    /*
     * sendVerificationEmail()
     * sends an email to the provided email address
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */    
    private function sendVerificationEmail() {
        
        $mail = new PHPMailer;

        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            
            $mail->IsSMTP();                                      // Set mailer to use SMTP
            $mail->Host = EMAIL_SMTP_HOST;  // Specify main and backup server
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;                               // Enable SMTP authentication
            $mail->Username = EMAIL_SMTP_USERNAME;                            // SMTP username
            $mail->Password = EMAIL_SMTP_PASSWORD;                           // SMTP password
            
            if (EMAIL_SMTP_ENCRYPTION) {
                
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;                  // Enable encryption, 'ssl' also accepted            
            }            
            
        } else {
            
            $mail->IsMail();            
        }
        
        $mail->From = EMAIL_VERIFICATION_FROM_EMAIL;
        $mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
        $mail->AddAddress($this->user_email);
        $mail->Subject = EMAIL_VERIFICATION_SUBJECT;
        $mail->Body    = EMAIL_VERIFICATION_CONTENT . EMAIL_VERIFICATION_URL.'/'.urlencode($this->user_id).'/'.urlencode($this->user_activation_hash);

        if(!$mail->Send()) {
            
           $this->errors[] = 'Mail could not be sent due to: ' . $mail->ErrorInfo;
           return false;
           
        } else {
            
            $this->errors[] = 'A verification mail has been sent successfully.';
            return true;
            
        }
        
    }
    
    /**
     * verifyNewUser()
     * checks the email/verification code combination and set the user's activation status to true (=1) in the database
     */
    public function verifyNewUser($user_id, $user_verification_code) {

        $sth = $this->db->prepare("UPDATE users SET user_active = 1, user_activation_hash = NULL WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash ;");
        $sth->execute(array(':user_id' => $user_id, ':user_activation_hash' => $user_verification_code));                                  

        if ($sth->rowCount() > 0) {

            $this->errors[] = "Activation was successful! You can now log in!";

        } else {

            $this->errors[] = "Sorry, no such id/verification code combination here...";

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
     * @param string $s Size in pixels, defaults to 50px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function setGravatarImageUrl($email, $s = 44, $d = 'mm', $r = 'pg', $atts = array() ) {
        
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        
        // the image url (on gravatarr servers), will return in something like
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
     * 
     */
    public function setPasswordResetDatabaseToken() {
        
        if (empty($_POST['user_name'])) {
          
            $this->errors[] = "Empty username";
            
        } else {
            
            // generate timestamp (to see when exactly the user (or an attacker) requested the password reset mail)
            // btw this is an integer ;)
            $temporary_timestamp = time();
            
            // generate random hash for email password reset verification (40 char string)
            $this->user_password_reset_hash = sha1(uniqid(mt_rand(), true));
                
            // TODO: this is not totally clean, as this is just the form provided username
            $this->user_name = htmlentities($_POST['user_name'], ENT_QUOTES);                
            
            $sth = $this->db->prepare("SELECT user_id, user_email FROM users WHERE user_name = :user_name ;");
            $sth->execute(array(':user_name' => $this->user_name));                    

            $count =  $sth->rowCount();            

            if ($count == 1) {

                // get result row (as an object)
                $result_user_row = $result = $sth->fetch();  
                
                // database query: 
                $sth2 = $this->db->prepare("UPDATE users 
                                           SET user_password_reset_hash = :user_password_reset_hash, 
                                               user_password_reset_timestamp = :user_password_reset_timestamp 
                                           WHERE user_name = :user_name ;");
                $sth2->execute(array(':user_password_reset_hash' => $this->user_password_reset_hash,
                                    ':user_password_reset_timestamp' => $temporary_timestamp,
                                    ':user_name' => $this->user_name));

                // check if exactly one row was successfully changed:
                $count =  $sth2->rowCount();            

                if ($count == 1) {

                    // define email
                    $this->user_email = $result_user_row->user_email;

                    return true;

                } else {

                    $this->errors[] = "Could not write token to database."; // maybe say something not that technical.

                }                    
                
            } else {

                $this->errors[] = "This username does not exist.";

            }
                
        }
        
        // return false (this method only returns true when the database entry has been set successfully)
        return false;        
    }
    
    public function sendPasswordResetMail() {
        
        $mail = new PHPMailer;

        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            
            $mail->IsSMTP();                                      // Set mailer to use SMTP
            $mail->Host = EMAIL_SMTP_HOST;  // Specify main and backup server
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;                               // Enable SMTP authentication
            $mail->Username = EMAIL_SMTP_USERNAME;                            // SMTP username
            $mail->Password = EMAIL_SMTP_PASSWORD;                           // SMTP password
            
            if (EMAIL_SMTP_ENCRYPTION) {
                
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;                  // Enable encryption, 'ssl' also accepted            
            }            
            
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
            
           $this->errors[] = 'Mail could not be sent due to: ' . $mail->ErrorInfo;
           return false;
           
        } else {
            
            $this->errors[] = 'A password reset mail has been sent successfully.';
            return true;
            
        }
        
    }    
    
    /**
     * 
     */
    public function verifypasswordrequest($user_name, $verification_code) {
                
        // TODO: this is not totally clean, as this is just the form provided username
        $this->user_name                = htmlentities($user_name, ENT_QUOTES);         
        $this->user_password_reset_hash = htmlentities($verification_code, ENT_QUOTES);    

        $sth = $this->db->prepare("SELECT user_id, user_password_reset_timestamp 
                                   FROM users 
                                   WHERE user_name = :user_name 
                                      && user_password_reset_hash = :user_password_reset_hash;");
        $sth->execute(array(':user_password_reset_hash' => $verification_code,
                            ':user_name' => $user_name));

        // if this user exists
        if ($sth->rowCount() == 1) {

            // get result row (as an object)
            $result_user_row = $sth->fetch();
            // 3600 seconds are 1 hour
            $timestamp_one_hour_ago = time() - 3600; 

            if ($result_user_row->user_password_reset_timestamp > $timestamp_one_hour_ago) {

                // verification was sucessful
                return true;

            } else {

                $this->errors[] = "Your reset link has expired. Please use the reset link within one hour.";
                return false;
            }

        } else {

            $this->errors[] = "Username/Verification code combination does not exist.";
            return false;
        }
        
    }
    
    public function setNewPassword() {
        
        // TODO: timestamp!
        
        if (!empty($_POST['user_name'])
            && !empty($_POST['user_password_reset_hash'])
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])) {
                
            if ($_POST['user_password_new'] === $_POST['user_password_repeat']) {
         
                if (strlen($_POST['user_password_new']) >= 6) {

                        // escapin' this, additionally removing everything that could be (html/javascript-) code
                        $this->user_name                = htmlentities($_POST['user_name'], ENT_QUOTES);
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

                        // write users new hash into database
                        $sth = $this->db->prepare("UPDATE users
                                            SET user_password_hash = :user_password_hash, 
                                                user_password_reset_hash = NULL, 
                                                user_password_reset_timestamp = NULL
                                            WHERE user_name = :user_name  
                                               && user_password_reset_hash = :user_password_reset_hash ;");

                        $sth->execute(array(':user_password_hash' => $this->user_password_hash,
                                            ':user_name' => $this->user_name,
                                            ':user_password_reset_hash' => $this->user_password_reset_hash));
                        
                        // check if exactly one row was successfully changed:
                        if ($sth->rowCount() == 1) {

                            $this->errors[] = "Password sucessfully changed!";
                            return true;

                        } else {

                            $this->errors[] = "Sorry, your password changing failed.";

                        }
                    
                } else {
                    
                    $this->errors[] = "Password too short, please request a new password reset.";
                    
                }
                
            } else {
                
                $this->errors[] = "Passwords dont match, please request a new password reset.";
                
            }
                
        }
        
        // default
        return false;
        
    }
    
}