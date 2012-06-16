<?php

/**
 * class Login
 * 
 * creates db connection, logs in the user, creates session
 * 
 * @author Panique <panique@web.de>
 * @version 1.0
 * @package login
 */

class Login {

    protected   $db         = null;                     // database connection
    private     $logged_in  = false;                    // status of login    
    public      $errors     = array();                  // collection of error messages
    public      $messages   = array();                  // collection of success / neutral messages
    
    
    public function __construct() {        
        
        if ($this->checkDatabase()) {                    // check for database connection
            
            session_start();                            // create session

            if ($this->logout()) {                      // checking for logout, performing login            
                // do nothing, you are logged out now   // this if construction just exists to prevent unnecessary method calls
            } elseif ($this->loginWithSessionData()) {
                $this->logged_in = true;
            } elseif ($this->loginWithPostData()) {
                $this->logged_in = true;
            }        

            $this->registerNewUser();                   // check for registration data            
        } else {
            $this->errors[] = "No MySQL connection.";
        }        
    }    
    
    
    private function checkDatabase() {
        if (!$this->db) {                                                       // does db connection exist ?
            include_once("config/db.php");                                      // include database constants
            $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);         // create db connection     
            return (!$this->db->connect_errno ? true : false);                  // if no connect errors return true else false
        }
    }
    

    private function loginWithSessionData() {
        if (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in']==1)) {
            return true;
        } else {
            return false;
        }
    }
    

    private function loginWithPostData() {
        
        if(isset($_POST["login"]) && !empty($_POST['user_name']) && !empty($_POST['user_password'])) {
            
            $user_name = $this->db->real_escape_string($_POST['user_name']);            
            $checklogin = $this->db->query("SELECT user_name, user_email, user_salt, user_password FROM users WHERE user_name = '".$user_name."';");
            
            if($checklogin->num_rows == 1) {
                $result_row = $checklogin->fetch_object();                
                if (hash("sha256", $_POST["user_password"].$result_row->user_salt) == $result_row->user_password) {
                    $_SESSION['user_name'] = $result_row->user_name;;
                    $_SESSION['user_email'] = $result_row->user_email;
                    $_SESSION['user_logged_in'] = 1;                    
                    return true;                    
                } else {
                    $this->errors[] = "Username and/or password wrong.";
                    return false;                    
                }                
            } else {                
                $this->errors[] = "Username and/or password wrong.";
                return false;
            }
        } elseif (isset($_POST["login"]) && !empty($_POST['user_name']) && empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        }      
        
    }
    
    
    public function logout() {
        
        if (isset($_GET["action"]) && $_GET["action"]=="logout") {
            $_SESSION = array();
            session_destroy();
            return true;
        }        
    }
    
    
    public function isLoggedIn() {
        /*  
         * SHORTHAND SYNTAX, as usual not documented by shitty php.net manual
         * $var = ($var > 2 ? true : false); // returns true/false
         * @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
         * 
         * this line simply says:   if ($this->logged_in == 1) { return true; }
         *                          else { return false; }
        */
        return $this->logged_in == 1;
    }


    private function registerNewUser() {

        if(isset($_POST["register"]) && !empty($_POST['user_name']) && !empty($_POST['user_password'])) {

                $user_name = $this->db->real_escape_string($_POST['user_name']);
                $user_password = $this->db->real_escape_string($_POST['user_password']);
                $user_email = $this->db->real_escape_string($_POST['user_email']);
                
                // generate 64 char long random string "salt", a string to "encrypt" the password hash
                // this is a basic salt, you might replace this with a more advanced function
                // @see http://en.wikipedia.org/wiki/Salt_(cryptography)
                $user_salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
                // double md5 hash the plain password + salt
                //$user_password_hashed = md5(md5($_POST['user_password'].$user_salt));
                
                // hash the combined string of password+salt via the sha256 algorithm, result is a 64 char string                 
                $user_password_hashed = hash("sha256", $user_password.$user_salt);
                

                $query_check_user_name = $this->db->query("SELECT * FROM users WHERE user_name = '".$user_name."'");

                if($query_check_user_name->num_rows == 1)
                {
                    $this->errors[] = "<p>Sorry, that user_name is taken. Please go back and try again.</p>";
                }
                else
                {
                    $query_new_user_insert = $this->db->query("INSERT INTO users (user_name, user_salt, user_password, user_email) VALUES('".$user_name."', '".$user_salt."', '".$user_password_hashed."', '".$user_email."')");
                    if($query_new_user_insert)
                    {
                        $this->messages[] = "<p>Your account was successfully created. Please <a href='index.php'>click here to login</a>.</p>";
                    }
                    else
                    {
                        $this->errors[] = "<p>Sorry, your registration failed. Please go back and try again.</p>";
                    }
                }
        }
    }


}
