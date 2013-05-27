<?php

/**
 * class Login
 * handles the user login/logout/session
 * 
 * @author Panique <panique@web.de>
 * @version 1.2
 */
class Login {

    private     $db_connection              = null;                     // database connection
    
    private     $user_id                    = null;                     // user's id
    private     $user_name                  = "";                       // user's name
    private     $user_email                 = "";                       // user's email
    private     $user_password_hash         = "";                       // user's hashed and salted password
    private     $user_is_logged_in          = false;                    // status of login

    public      $errors                     = array();                  // collection of error messages
    public      $messages                   = array();                  // collection of success / neutral messages
    
    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct() {
        
        // create/read session
        session_start();                                        

        // check the possible login actions:
        // 1. logout (happen when user clicks logout button)
        // 2. login via session data (happens each time user opens a page on your php project AFTER he has sucessfully logged in via the login form)
        // 3. login via post data, which means simply logging in via the login form. after the user has submit his login/password successfully, his
        //    logged-in-status is written into his session data on the server. this is the typical behaviour of common login scripts.
        
        // if user tried to log out
        if (isset($_GET["logout"])) {

            $this->doLogout();
                    
        }
        // if user has an active session on the server
        elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {
            
            $this->loginWithSessionData();      
            
            // checking for form submit from editing screen
            if (isset($_POST["user_edit_submit_name"])) {
                
                $this->editUserName();
                
            } elseif (isset($_POST["user_edit_submit_email"])) {
                
                $this->editUserEmail();
                
            } 

        // if user just submitted a login form
        } elseif (isset($_POST["login"])) {

                $this->loginWithPostData();
                
        }
        
    }    
    

    private function loginWithSessionData() {
        
        // set logged in status to true, because we just checked for this:
        // !empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)
        // when we called this method (in the constructor)
        $this->user_is_logged_in = true;
        
    }
    

    private function loginWithPostData() {
        
        // if POST data (from login form) contains non-empty user_name and non-empty user_password
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
            
            // create a database connection, using the constants from config/db.php (which we loaded in index.php)
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {
                
                // escape the POST stuff
                $this->user_name = $this->db_connection->real_escape_string($_POST['user_name']);            
                // database query, getting all the info of the selected user
                $checklogin = $this->db_connection->query("SELECT user_id, user_name, user_email, user_password_hash, user_active FROM users WHERE user_name = '".$this->user_name."';");

                // if this user exists
                if ($checklogin->num_rows == 1) {

                    // get result row (as an object)
                    $result_row = $checklogin->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided passwords fits to the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {

                        if ($result_row->user_active == 1) {
                        
                            // write user data into PHP SESSION [a file on your server]
                            $_SESSION['user_id'] = $result_row->user_id;
                            $_SESSION['user_name'] = $result_row->user_name;
                            $_SESSION['user_email'] = $result_row->user_email;
                            $_SESSION['user_logged_in'] = 1;

                            // set the login status to true
                            $this->user_is_logged_in = true; 
                        
                        } else {
                            
                            $this->errors[] = "Your account is not activated yet. Please click on the confirm link in the mail.";
                            
                        }

                    } else {

                        $this->errors[] = "Wrong password. Try again.";

                    }                

                } else {

                    $this->errors[] = "This user does not exist.";
                }
                
            } else {
                
                $this->errors[] = "Database connection problem.";
            }
            
        } elseif (empty($_POST['user_name'])) {

            $this->errors[] = "Username field was empty.";

        } elseif (empty($_POST['user_password'])) {

            $this->errors[] = "Password field was empty.";
        }           
        
    }
    
    /**
     * perform the logout
     */
    public function doLogout() {
            
            $_SESSION = array();
            session_destroy();
            $this->user_is_logged_in = false;
            $this->messages[] = "You have been logged out.";     
            
    }
    
    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn() {
        
        return $this->user_is_logged_in;
        
    }
    
    /**
     * edit the user's name, provided in the editing form
     */
    public function editUserName() {
        
        
        if (!empty($_POST['user_name']) && $_POST['user_name'] == $_SESSION["user_name"]) {
            
            $this->errors[] = "Sorry, that user name is the same like your current one.<br/>Please choose another one.";
        
        } 
        // username cannot be empty and must be azAZ09 and 2-64 characters
        elseif (!empty($_POST['user_name']) && preg_match("/^(?=.{2,64}$)[a-zA-Z][a-zA-Z0-9]*(?: [a-zA-Z0-9]+)*$/", $_POST['user_name'])) {
            
            
            // creating a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escapin' this
                $this->user_name = $this->db_connection->real_escape_string($_POST['user_name']);
                $this->user_name = substr($this->user_name, 0, 64);
                $this->user_id = $this->db_connection->real_escape_string($_SESSION['user_id']); // not really necessary, but just in case...
                
                // check if new username already exists
                $query_check_user_name = $this->db_connection->query("SELECT * FROM users WHERE user_name = '".$this->user_name."';");

                if ($query_check_user_name->num_rows == 1) {

                    $this->errors[] = "Sorry, that username is already taken.<br/>Please choose another one.";

                } else {
                    
                    // write user's new data into database
                    $query_edit_user_name = $this->db_connection->query("UPDATE users SET user_name = '$this->user_name' WHERE user_id = '$this->user_id';");

                    if ($query_edit_user_name) {

                        $_SESSION['user_name'] = $this->user_name;
                        $this->messages[] = "Your username has been changed sucessfully. New username is $this->user_name.";

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
    public function editUserEmail() {
        
        
        if (!empty($_POST['user_email']) && $_POST['user_email'] == $_SESSION["user_email"]) {
            
            $this->errors[] = "Sorry, that email is the same like your current one.<br/>Please choose another one.";
        
        } 
        // user mail cannot be empty and must be in email format
        elseif (!empty($_POST['user_email']) && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            
            
            // creating a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escapin' this
                $this->user_email = $this->db_connection->real_escape_string($_POST['user_email']);
                // prevent database flooding
                $this->user_email = substr($this->user_email, 0, 64); 
                // not really necessary, but just in case...
                $this->user_id = $this->db_connection->real_escape_string($_SESSION['user_id']); 
                                   
                // write users new data into database
                $query_edit_user_email = $this->db_connection->query("UPDATE users SET user_email = '$this->user_email' WHERE user_id = '$this->user_id';");

                if ($query_edit_user_email) {

                    $_SESSION['user_email'] = $this->user_email;
                    $this->messages[] = "Your email adress has been changed sucessfully. New email adress is $this->user_email.";

                } else {

                    $this->errors[] = "Sorry, your email changing failed.";

                }
                
            }
            
        } else {
            
            $this->errors[] = "Sorry, your chosen email does not fit into the naming pattern.";
            
        }        
        
    }    

}