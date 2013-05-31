<?php

/**
 * class Registration
 * handles the user registration
 * 
 * @author Panique <panique@web.de>
 * @version 1.1
 */
class Registration {

    private     $db_connection              = null;                     // database connection   
    
    private     $user_name                  = "";                       // user's name
    private     $user_email                 = "";                       // user's email
    private     $user_password              = "";                       // user's password (what comes from POST)
    private     $user_password_hash         = "";                       // user's hashed and salted password
    private     $user_activation_hash       = "";                       // user's random hash string, necessary for email activation
    
    private     $hash_cost_factor           = array();                  // (optional) cost factor for the hash calculation
    
    public      $registration_successful    = false;

    public      $errors                     = array();                  // collection of error messages
    public      $messages                   = array();                  // collection of success / neutral messages
    
    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct() {
        
            // if we have such a POST request, call the registerNewUser() method
            if (isset($_POST["register"])) {
                
                $this->registerNewUser();
                
            }        
            
            // if we have such a GET request, call the verifyNewUser() method
            if (isset($_GET["email"]) && isset($_GET["verification_code"])) {
                
                $this->verifyNewUser();
                
            }
    }

    /**
     * registerNewUser()
     * 
     * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
     * everything is fine
     */
    private function registerNewUser() {
        
        if (empty($_POST['user_name'])) {
          
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
            
            $this->errors[] = "Your email adress is not in a valid email format";
        
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
            
            // TODO: the above check is redundand, but from a developer's perspective it makes clear
            // what exactly we want to reach to go into this if-block

            // creating a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escapin' this
                $this->user_name            = $this->db_connection->real_escape_string($_POST['user_name']);
                $this->user_email           = $this->db_connection->real_escape_string($_POST['user_email']);

                // now it gets a little bit crazy: check if we have a constant HASH_COST_FACTOR defined (in config/hashing.php),
                // if so: put the value into $this->hash_cost_factor, if not, make $this->hash_cost_factor = null
                $this->hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
                
                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
                // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
                // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
                // want the paramter: as an array with, currently only used with 'cost' => XX.
                $this->user_password_hash = password_hash($this->user_password, PASSWORD_DEFAULT, array('cost' => $this->hash_cost_factor));

                // check if user already exists
                $query_check_user_name = $this->db_connection->query("SELECT * FROM users WHERE user_name = '".$this->user_name."';");

                if ($query_check_user_name->num_rows == 1) {

                    $this->errors[] = "Sorry, that user name is already taken.<br/>Please choose another one.";

                } else {
                    
                    // generate random hash for email verification (40 char string)
                    $this->user_activation_hash = sha1(uniqid(mt_rand(), true));

                    // write new users data into database
                    $query_new_user_insert = $this->db_connection->query("INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash) VALUES('".$this->user_name."', '".$this->user_password_hash."', '".$this->user_email."', '".$this->user_activation_hash."');");

                    if ($query_new_user_insert) {
                        
                        // send a verification email
                        if ($this->sendVerificationEmail()) {
                            
                            // when mail has been send successfully
                            $this->messages[] = "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.";
                            $this->registration_successful = true;
                            
                        } else {

                            // delete this users account immediatly, as we could not send a verification email
                            // the row (which will be deleted) is identified by mySQLi's insert_id property (= the last inserted row)
                            // @see php.net/manual/en/mysqli.insert-id.php
                            $this->db_connection->query("DELETE FROM users WHERE user_id = '".$this->db_connection->insert_id."';");
                            $this->errors[] = "Sorry, we could not send you an verification mail. Your account has NOT been created.";

                        }

                    } else {

                        $this->errors[] = "Sorry, your registration failed. Please go back and try again.";

                    }
                }

            } else {

                $this->errors[] = "Sorry, no database connection.";

            }
            
        } else {
            
            $this->errors[] = "An unknown error occured.";
            
        }
        
    }

    /*
     * sendVerificationEmail()
     * sends an email to the provided email adress
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    public function sendVerificationEmail() {
        
        $to      = $this->user_email;
        $subject = EMAIL_VERIFICATION_SUBJECT;
        
        $link    = EMAIL_VERIFICATION_URL.'?email='.$this->user_email.'&verification_code='.$this->user_activation_hash;
        
        // the link to your registration.php, please set this value in config/urls.php
        $body = EMAIL_VERIFICATION_CONTENT.' <a href="'.$link.'">'.$link.'</a>';

        // stuff for HTML mails, test this is you feel adventurous ;)
        $header  = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$header .= "To: <$to>" . "\r\n";
        $header .= 'From: '.EMAIL_VERIFICATION_FROM."\r\n";

        if (mail($to, $subject, $body, $header)) {
            
            $this->messages[] = "Verification Mail successfully sent!";
            return true;
            
        } else {
            
            $this->errors[] = "Verification Mail NOT successfully sent!";
            return false;
            
        }
        
    }
    
    /**
     * verifyNewUser()
     * checks the email/verification code combination and set the user's activation status to true (=1) in the database
     */
    public function verifyNewUser() {
        
        // creating a database connection
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // if no connection errors (= working database connection)
        if (!$this->db_connection->connect_errno) {
            
            $this->user_email           = $this->db_connection->real_escape_string($_GET['email']);
            $this->user_activation_hash = $this->db_connection->real_escape_string($_GET['verification_code']);
            
            //
            $this->db_connection->query('UPDATE users SET user_active = 1, user_activation_hash = NULL WHERE user_email = "'.$this->user_email.'" AND user_activation_hash = "'.$this->user_activation_hash.'";');
            
            if ($this->db_connection->affected_rows > 0) {
                
                $this->messages[] = "Activation was successful! You can now log in!";
                
            } else {
            
                $this->errors[] = "Sorry, no such email/verification code combination here...";
                
            }
            
        } else {

            $this->errors[] = "Sorry, no database connection.";

        }
        

        
    }
    
}