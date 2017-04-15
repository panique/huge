<?php

/**
 * class Login * 
 * handles the user login/logout/session
 * 
 * @author Panique <panique@web.de>
 * @version 1.1
 */

class Login {

    private     $connection                 = null;                     // database connection   
    
    private     $user_name                  = "";                       // user's name
    private     $user_email                 = "";                       // user's email
    private     $user_password              = "";                       // user's password (what comes from POST)
    private     $user_password_hash         = "";                       // user's hashed and salted password
    private     $user_is_logged_in          = false;                    // status of login
    
    public      $registration_successful    = false;
    
    public      $view_user_name             = "";
    public      $view_user_email            = "";

    public      $errors                     = array();                  // collection of error messages
    public      $messages                   = array();                  // collection of success / neutral messages
    
    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct($db) {                     
        
        $this->connection = $db;                   // get the database connection
        
        if ($this->connection) {                                            // check for database connection
            
            session_start();                                        // create/read session
            
            if (isset($_POST["register"])) {
                
                $this->registerNewUser();
                            
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
            
            $this->errors[] = "Database error. Try again.";
        }
        
        // cookie handling user name
        if (isset($_COOKIE['user_name'])) {
            $this->view_user_name = strip_tags($_COOKIE["user_name"]);
        } else {
            $this->view_user_name = "Username";
        }
        
    }    
    

    private function loginWithSessionData() {
        
        $this->user_is_logged_in = true;
        
    }
    

    private function loginWithPostData() {
            
            $this->user_name = pg_escape_string($_POST['user_name']);            
            $checklogin = pg_query($this->connection, "SELECT user_name, user_email, user_password FROM users WHERE user_name = '".$this->user_name."';");
            
            if($checklogin->num_rows == 1) {
                
                $result_row = pg_fetch_object($checklogin);
                
                if (crypt($_POST['user_password'], $result_row->user_password) == $result_row->user_password) {
                    
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
    }
    
    
    public function isUserLoggedIn() {
        
        return $this->user_is_logged_in;
        
    }

    private function registerNewUser() {
        
        if (empty($_POST['user_name'])) {
          
            $this->errors[] = "Empty Username";

        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
          
            $this->errors[] = "Empty Password";            
            
        } elseif ($_POST['user_password_new'] != $_POST['user_password_repeat']) {
            
            $this->errors[] = "Password and password repeat are not the same"; 
            
        } elseif (strlen($_POST['user_name']) <= 2){      
            
            $this->errors[] = "Username must be longer than 2 characters.";
            
        } elseif (strlen($_POST['user_name']) >= 64){
            
            $this->errors[] = "Your username cannot be longer than 64 characters.";
                    
        } elseif (strlen($_POST['user_password_new']) >= 64){
            
            $this->errors[] = "Try a shorter password, it should be easier to remember.";
            
        } elseif (strlen($_POST['user_password_new']) <= 5 ){
            
            $this->errors[] = "Password must be longer than 5 characters for your safety.";
            
        } elseif ((filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) == FALSE) && !empty($_POST['user_email'])){
            
            $this->errors[] = "Invalid email address.";   
            
        } elseif ( !empty($_POST['user_name']) && !empty($_POST['user_password_new']) && !empty($_POST['user_password_repeat']) && ($_POST['user_password_new'] == $_POST['user_password_repeat'])) {
                
                // escapin' this
                $this->user_name            = pg_escape_string($_POST['user_name']);
                $this->user_password        = pg_escape_string($_POST['user_password_new']);
                $this->user_password_repeat = pg_escape_string($_POST['user_password_repeat']);
                $this->user_email           = pg_escape_string($_POST['user_email']);
                
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

                $query_check_user_name = pg_query($this->connection, "SELECT * FROM users WHERE user_name = '".$this->user_name."'");

                if($query_check_user_name->num_rows == 1) {
                    
                    $this->errors[] = "Sorry, that user name is already taken.<br/>Please choose another one.";
                    
                } else {
                    
                    $query_new_user_insert = pg_query($this->connection, "INSERT INTO users (user_name, user_password, user_email) VALUES('".$this->user_name."', '".$this->user_password_hash."', '".$this->user_email."')");
                    
                    if ($query_new_user_insert) {
                        
                        $this->messages[] = "Your account was successfully created.<br/>Please <a href='index.php' class='green_link'>click here to login</a>.";
                        $this->registration_successful = true;
                        
                    } else {
                        
                        $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                        
                    }
                }
        }
    }
    
    function loggedIn($img){
        if(isset($img)){
            $image = '<img id="login_avatar" src="views/img/ani_avatar_static_01.png' . $img . '" style="width:125px; height:125px;" />';
        }else{
            $image = "";
        }
        $return = '<div id="login_avatar" class="login_avatar_div">' . $img . '</div><div style="width: 110px; height: 50px; float:left; margin:0; font-family: Droid Sans, sans-serif; color:#666666; font-size:12px; border:0; height:100%; line-height: 50px; padding-left:20px; padding-right: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">'. $_SESSION["user_name"] . '</div><div class="login_logout"><a href="index.php?logout" style="width:49px; height:19px; padding-top: 31px; display:block; text-align: center; font-size:10px; font-family: Droid Sans, sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" >Logout</a></div>';
        return $return;
     }
     
     public $notLoggedIn = '<div style="width: 110px; height: 50px; float:left; margin:0; font-family: Droid Sans, sans-serif; color:#666666; font-size:12px; border:0; height:100%; line-height: 50px; padding-left:20px; padding-right: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><a href="/register.php" style="width:49px; height:19px; padding-top: 31px; display:block; text-align: center; font-size:10px; font-family: Droid Sans, sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" >Register</a> or <a href="/login.php" style="width:49px; height:19px; padding-top: 31px; display:block; text-align: center; font-size:10px; font-family: Droid Sans, sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" >Login</a></div></div>';    

}