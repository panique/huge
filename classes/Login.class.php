<?php

/**
 * class Login * 
 * handles the user login/logout/session
 * 
 * @author Panique <panique@web.de>
 * @version 1.1
 */
class Login {

    private     $connection                         = null;                     // database connection   
    
    private     $user_name                  = "";                       // user's name
    private     $user_email                 = "";                       // user's email
    private     $user_password              = "";                       // user's password (what comes from POST)
    private     $user_password_hash         = "";                       // user's hashed and salted password
    private     $user_is_logged_in          = false;                    // status of login
    
    public      $registration_successful    = false;
    
    public      $view_user_name           = "";
    public      $view_user_email          = "";

    public      $errors                     = array();                  // collection of error messages
    public      $messages                   = array();                  // collection of success / neutral messages
    
    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct(Database $db) {                     // (Database $db) says: the _construct method expects a parameter, but it has to be an object of the class "Database"
        
        $this->connection = $db->getDatabaseConnection();                   // get the database connection
        
        if ($this->connection) {                                            // check for database connection
            
            session_start();                                        // create/read session
            
            if (isset($_POST["register"])) {
                
                $this->registerNewUser();
                
            } elseif (isset($_GET["logout"])) {
                
                $this->doLogout();
                            
            } elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {
                
                $this->loginWithSessionData();                
                
            } elseif (isset($_POST["login"])) {
                
                if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
                    
                    $this->loginWithPostData();
                
                } elseif (empty($_POST['user_name'])) {
                    
                    $this->errors[] = "Username field was empty.";
                    
                } elseif (empty($_POST['user_password'])) {
                    
                    $this->errors[] = "Password field was empty.";
                    
                }
                
            }
            
        } else {
            
            $this->errors[] = "No MySQL connection.";
        }
        
        // cookie handling user name
        if (isset($_COOKIE['user_name'])) {
            $this->view_user_name = strip_tags($_COOKIE["user_name"]);
        } else {
            $this->view_user_name = "Username";
        }
        
        // cookie handling avatar link
        if (isset($_COOKIE['user_email'])) {
            $this->avatar_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($_COOKIE['user_email']))) . "?d=mm&s=125";
        } else {
            // override 
            $this->avatar_url = "http://www.gravatar.com/avatar/" . md5("xxxxxx@xxxxxxxxxx.com") . "?d=mm&s=125";
        }
        
    }    
    

    private function loginWithSessionData() {
        
        $this->user_is_logged_in = true;
        
    }
    

    private function loginWithPostData() {
            
            $this->user_name = $this->connection->real_escape_string($_POST['user_name']);            
            $checklogin = $this->connection->query("SELECT user_name, user_email, user_password_hash FROM users WHERE user_name = '".$this->user_name."';");
            
            if($checklogin->num_rows == 1) {
                
                $result_row = $checklogin->fetch_object();
                
                if (crypt($_POST['user_password'], $result_row->user_password_hash) == $result_row->user_password_hash) {
                    
                    /**
                     *  write user data into PHP SESSION [a file on your server]
                     */
                    $_SESSION['user_name'] = $result_row->user_name;
                    $_SESSION['user_email'] = $result_row->user_email;
                    $_SESSION['user_logged_in'] = 1;
                    
                    /**
                     *  write user data into COOKIE [a file in user's browser]
                     */
                    setcookie("user_name", $result_row->user_name, time() + (3600*24*100));
                    setcookie("user_email", $result_row->user_email, time() + (3600*24*100));
                    
                    $this->user_is_logged_in = true;
                    return true;          
                    
                } else {
                    
                    $this->errors[] = "Wrong password. Try again.";
                    return false;  
                    
                }                
                
            } else {
                
                $this->errors[] = "This user does not exist.";
                return false;
            }        
    }
    
    
    public function doLogout() {
        
            $_SESSION = array();
            session_destroy();
            $this->user_is_logged_in = false;
            $this->messages[] = "You have been logged out.";
    }
    
    
    public function isUserLoggedIn() {
        
        return $this->user_is_logged_in;
        
    }
    
    
    public function displayRegisterPage() {
        
        if (isset($_GET["register"])) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }


    private function registerNewUser() {
        
        if (empty($_POST['user_name'])) {
          
            $this->errors[] = "Empty Username";

        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
          
            $this->errors[] = "Empty Password";            
            
        } elseif ($_POST['user_password_new'] != $_POST['user_password_repeat']) {
          
            $this->errors[] = "Password and password repeat are not the same";            
                        
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password_new']) && !empty($_POST['user_password_repeat']) && ($_POST['user_password_new'] == $_POST['user_password_repeat'])) {

                // escapin' this
                $this->user_name            = $this->connection->real_escape_string($_POST['user_name']);
                $this->user_password        = $this->connection->real_escape_string($_POST['user_password_new']);
                $this->user_password_repeat = $this->connection->real_escape_string($_POST['user_password_repeat']);
                $this->user_email           = $this->connection->real_escape_string($_POST['user_email']);
                
                // cut data down to max 64 chars to prevent database flooding
                $this->user_name            = substr($this->user_name, 0, 64);
                $this->user_password        = substr($this->user_password, 0, 64);
                $this->user_password_repeat = substr($this->user_password_repeat, 0, 64);
                $this->user_email           = substr($this->user_email, 0, 64);
                
                // generate random string "salt", a string to "encrypt" the password hash
                // this is a basic salt, you might replace this with a more advanced function
                // @see http://en.wikipedia.org/wiki/Salt_(cryptography)

                function get_salt($length) {
                    
                    $options = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
                    $salt = '';

                    for ($i = 0; $i <= $length; $i ++) {
                        $options = str_shuffle ( $options );
                        $salt .= $options [rand ( 0, 63 )];
                    }
                    return $salt;
                }
                
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
                $max_salt = CRYPT_SALT_LENGTH;

                //blowfish hashing with a salt as follows: "$2a$", a two digit cost parameter, "$", and 22 base 64
                //here you can define the hashing algorithm.
                //@see: php.net/manual/en/function.crypt.php
                $hashing_algorithm = '$2a$10$';

                //get the longest salt, could set to 22 crypt ignores extra data
                $salt = get_salt ( $max_salt );

                //append salt2 data to the password, and crypt using salt, results in a 60 char output
                $this->user_password_hash = crypt ( $this->user_password, $hashing_algorithm . $salt );               

                $query_check_user_name = $this->connection->query("SELECT * FROM users WHERE user_name = '".$this->user_name."'");

                if($query_check_user_name->num_rows == 1) {
                    
                    $this->errors[] = "Sorry, that user name is already taken.<br/>Please choose another one.";
                    
                } else {
                    
                    $query_new_user_insert = $this->connection->query("INSERT INTO users (user_name, user_password_hash, user_email) VALUES('".$this->user_name."', '".$this->user_password_hash."', '".$this->user_email."')");
                    
                    if ($query_new_user_insert) {
                        
                        $this->messages[] = "Your account was successfully created.<br/>Please <a href='index.php' class='green_link'>click here to login</a>.";
                        $this->registration_successful = true;
                        
                    } else {
                        
                        $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                        
                    }
                }
        }
    }


}