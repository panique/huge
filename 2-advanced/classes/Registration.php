<?php

/**
 * class Registration
 * handles the user registration
 * 
 * @author Panique <panique@web.de>
 * @version 1.1
 */
class Registration
{
    private     $db_connection              = null;                     // database connection   
    
    private     $user_name                  = "";                       // user's name
    private     $user_email                 = "";                       // user's email
    private     $user_password              = "";                       // user's password (what comes from POST)
    private     $user_password_hash         = "";                       // user's hashed and salted password
    private     $user_activation_hash       = "";                       // user's random hash string, necessary for email activation
    
    private     $hash_cost_factor           = null;                     // (optional) cost factor for the hash calculation
    
    public      $registration_successful    = false;
    public      $verification_successful    = false;

    public      $errors                     = array();                  // collection of error messages
    public      $messages                   = array();                  // collection of success / neutral messages
    
    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct() {
        
            session_start();
        
            // if we have such a POST request, call the registerNewUser() method
            if (isset($_POST["register"])) {
                
                $this->registerNewUser();
                
            } 
            
            // if we have such a GET request, call the verifyNewUser() method
            if (isset($_GET["id"]) && isset($_GET["verification_code"])) {
                
                $this->verifyNewUser();
                
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

    /**
     * registerNewUser()
     * 
     * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
     * everything is fine
     */
    private function registerNewUser() {
        
        if (strtolower($_POST["captcha"]) != strtolower($_SESSION['captcha'])) {
        
            $this->errors[] = "Captcha was wrong!";
            
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
            
            // TODO: the above check is redundant, but from a developer's perspective it makes clear
            // what exactly we want to reach to go into this if-block

			// if database connection opened
			if ($this->databaseConnection()) {
               
                // we just remove extra space
                $this->user_name  = trim($_POST['user_name']);
                $this->user_email = trim($_POST['user_email']);
                
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
                $query_check_user_name = $this->db_connection->prepare('SELECT user_name FROM users WHERE user_name=:user_name');
				$query_check_user_name->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
				$query_check_user_name->execute();

                if ($query_check_user_name->fetchColumn() != false) {

                    $this->errors[] = "Sorry, that username is already taken. Please choose another one.";

                } else {
                    
                    // generate random hash for email verification (40 char string)
                    $this->user_activation_hash = sha1(uniqid(mt_rand(), true));

                    // write new users data into database
                    $query_new_user_insert = $this->db_connection->prepare('INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash) VALUES(:user_name, :user_password_hash, :user_email, :user_activation_hash)');
					$query_new_user_insert->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
					$query_new_user_insert->bindValue(':user_password_hash', $this->user_password_hash, PDO::PARAM_STR);
					$query_new_user_insert->bindValue(':user_email', $this->user_email, PDO::PARAM_STR);
					$query_new_user_insert->bindValue(':user_activation_hash', $this->user_activation_hash, PDO::PARAM_STR);
					$query_new_user_insert->execute();

                    // id of new user
                    // mySQLi's insert_id property (= the last inserted row)
                    // @see php.net/manual/en/mysqli.insert-id.php
                    $this->user_id = $this->db_connection->lastInsertId();
                    
                    if ($query_new_user_insert) {
                        
                        // send a verification email
                        if ($this->sendVerificationEmail()) {
                            
                            // when mail has been send successfully
                            $this->messages[] = "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.";
                            $this->registration_successful = true;
                            
                        } else {

                            // delete this users account immediately, as we could not send a verification email
							$query_delete_user = $this->db_connection->prepare('DELETE FROM users WHERE user_id=:user_id');
							$query_delete_user->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
							$query_delete_user->execute();
							
                            $this->errors[] = "Sorry, we could not send you an verification mail. Your account has NOT been created.";

                        }

                    } else {

                        $this->errors[] = "Sorry, your registration failed. Please go back and try again.";

                    }
                }

            }
            
        } else {
            
            $this->errors[] = "An unknown error occured.";
            
        }
        
    }

    /*
     * sendVerificationEmail()
     * sends an email to the provided email address
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    public function sendVerificationEmail() {
        
        $mail = new PHPMailer;

        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
        
        $mail->From = EMAIL_VERIFICATION_FROM;
        $mail->FromName = EMAIL_VERIFICATION_FROM_NAME;        
        $mail->AddAddress($this->user_email);
        $mail->Subject = EMAIL_VERIFICATION_SUBJECT;
               
        $link = EMAIL_VERIFICATION_URL.'?id='.urlencode($this->user_id).'&verification_code='.urlencode($this->user_activation_hash);
        
        // the link to your register.php, please set this value in config/email_verification.php
        $mail->Body = EMAIL_VERIFICATION_CONTENT.' '.$link;
        
        if(!$mail->Send()) {
            
            $this->errors[] = "Verification Mail NOT successfully sent! Error: " . $mail->ErrorInfo;
            return false;
           
        } else {
            
            $this->messages[] = "Verification Mail successfully sent!";
            return true;
            
        }
        
    }
    
    /**
     * verifyNewUser()
     * checks the id/verification code combination and set the user's activation status to true (=1) in the database
     */
    public function verifyNewUser() {
        
		// if database connection opened
		if ($this->databaseConnection()) {
            
            $this->user_id = intval(trim($_GET['id']));
            $this->user_activation_hash = $_GET['verification_code'];
            
            //
			$query_update_user = $this->db_connection->prepare('UPDATE users SET user_active = 1, user_activation_hash = NULL WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash');
			$query_update_user->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
			$query_update_user->bindValue(':user_activation_hash', $this->user_activation_hash, PDO::PARAM_STR);
			$query_update_user->execute();

            if ($query_update_user->rowCount() > 0) {
                
                $this->verification_successful = true;
                $this->messages[] = "Activation was successful! You can now log in!";

            } elseif($this->db_connection->errno > 0) {

                $this->errors[] = "Sorry, MySQL is reporting an error. Check your configuration.";

            } else {
            
                $this->errors[] = "Sorry, no such id/verification code combination here...";
                
            }
            
        }
        
    }
    
}
