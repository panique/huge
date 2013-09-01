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
    private $db_connection            = null;    // database connection   
    private $lang                     = array(); // array with translation of language strings

    public  $registration_successful  = false;
    public  $verification_successful  = false;

    public  $errors                   = array(); // collection of error messages
    public  $messages                 = array(); // collection of success / neutral messages

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */    
    public function __construct()
    {
        session_start();

        // Create internal reference to global array with translation of language strings
        $this->lang = & $GLOBALS['phplogin_lang'];

        // if we have such a POST request, call the registerNewUser() method
        if (isset($_POST["register"])) {

            $this->registerNewUser($_POST['user_name'], $_POST['user_email'], $_POST['user_password_new'], $_POST['user_password_repeat'], $_POST["captcha"]);

        // if we have such a GET request, call the verifyNewUser() method
        } else if (isset($_GET["id"]) && isset($_GET["verification_code"])) {

            $this->verifyNewUser($_GET["id"], $_GET["verification_code"]);

        }
    }

    /**
     * Checks if database connection is opened and open it if not
     */
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
                $this->errors[] = $this->lang['Database error'];
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
    private function registerNewUser($user_name, $user_email, $user_password, $user_password_repeat, $captcha)
    {
        // we just remove extra space on username and email
        $user_name  = trim($user_name);
        $user_email = trim($user_email);

        // check provided data validity
        if (strtolower($captcha) != strtolower($_SESSION['captcha'])) {

            $this->errors[] = $this->lang['Wrong captcha'];

        } elseif (empty($user_name)) {

            $this->errors[] = $this->lang['Empty Username'];

        } elseif (empty($user_password) || empty($user_password_repeat)) {

            $this->errors[] = $this->lang['Empty Password'];

        } elseif ($user_password !== $user_password_repeat) {

            $this->errors[] = $this->lang['Bad confirm password'];

        } elseif (strlen($user_password) < 6) {

            $this->errors[] = $this->lang['Password too short'];

        } elseif (strlen($user_name) > 64 || strlen($user_name) < 2) {

            $this->errors[] = $this->lang['Username bad length'];

        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $user_name)) {

            $this->errors[] = $this->lang['Invalid username'];

        } elseif (empty($user_email)) {

            $this->errors[] = $this->lang['Empty email'];

        } elseif (strlen($user_email) > 64) {

            $this->errors[] = $this->lang['Email too long'];

        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {

            $this->errors[] = $this->lang['Invalid email'];

        // finally if all the above checks are ok
        } else if ($this->databaseConnection()) {

            // check if username or email already exists
            $query_check_user_name = $this->db_connection->prepare('SELECT user_name, user_email FROM users WHERE user_name=:user_name OR user_email=:user_email');
            $query_check_user_name->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $query_check_user_name->bindValue(':user_email', $user_email, PDO::PARAM_STR);
            $query_check_user_name->execute();
            $result = $query_check_user_name->fetchAll();

            // if username or/and email find in the database
            if (count($result) > 0) {
                for ($i = 0; $i < count($result); $i++) {
                    $this->errors[] = ($result[$i]['user_name'] == $user_name) ? $this->lang['Username exist'] : $this->lang['Email exist'];
                }
            } else {
                // check if we have a constant HASH_COST_FACTOR defined (in config/hashing.php),
                // if so: put the value into $hash_cost_factor, if not, make $hash_cost_factor = null
                $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);

                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
                // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
                // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
                // want the parameter: as an array with, currently only used with 'cost' => XX.
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
                // generate random hash for email verification (40 char string)
                $user_activation_hash = sha1(uniqid(mt_rand(), true));

                // write new users data into database
                $query_new_user_insert = $this->db_connection->prepare('INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash, user_registration_ip, user_registration_datetime) VALUES(:user_name, :user_password_hash, :user_email, :user_activation_hash, :user_registration_ip, now())');
                $query_new_user_insert->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_registration_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $query_new_user_insert->execute();

                // id of new user
                $user_id = $this->db_connection->lastInsertId();

                if ($query_new_user_insert) {

                    // send a verification email
                    if ($this->sendVerificationEmail($user_id, $user_email, $user_activation_hash)) {

                        // when mail has been send successfully
                        $this->messages[] = "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.";
                        $this->registration_successful = true;

                    } else {

                        // delete this users account immediately, as we could not send a verification email
                        $query_delete_user = $this->db_connection->prepare('DELETE FROM users WHERE user_id=:user_id');
                        $query_delete_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                        $query_delete_user->execute();

                        $this->errors[] = $this->lang['Verification mail error'];

                    }
                } else {
                    $this->errors[] = $this->lang['Registration failed'];
                }
            }
        }
    }

    /*
     * sendVerificationEmail()
     * sends an email to the provided email address
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    public function sendVerificationEmail($user_id, $user_email, $user_activation_hash)
    {
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
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_VERIFICATION_SUBJECT;

        $link = EMAIL_VERIFICATION_URL.'?id='.urlencode($user_id).'&verification_code='.urlencode($user_activation_hash);

        // the link to your register.php, please set this value in config/email_verification.php
        $mail->Body = EMAIL_VERIFICATION_CONTENT.' '.$link;

        if(!$mail->Send()) {

            $this->errors[] = $this->lang['Verification mail not sent'] . $mail->ErrorInfo;
            return false;

        } else {

            $this->messages[] = $this->lang['Verification mail sent'];
            return true;

        }
    }

    /**
     * verifyNewUser()
     * checks the id/verification code combination and set the user's activation status to true (=1) in the database
     */
    public function verifyNewUser($user_id, $user_activation_hash)
    {
        // if database connection opened
        if ($this->databaseConnection()) {

            // try to update user with specified information
            $query_update_user = $this->db_connection->prepare('UPDATE users SET user_active = 1, user_activation_hash = NULL WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash');
            $query_update_user->bindValue(':user_id', intval(trim($user_id)), PDO::PARAM_INT);
            $query_update_user->bindValue(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);
            $query_update_user->execute();

            if ($query_update_user->rowCount() > 0) {

                $this->verification_successful = true;
                $this->messages[] = $this->lang['Activation successful'];

            } else {

                $this->errors[] = $this->lang['Activation error'];

            }

        }

    }

}
