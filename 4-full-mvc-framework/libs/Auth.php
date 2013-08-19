<?php

class Auth {

    public static function handleLogin() {
        
        Session::init();
        
        // if user is still not logged in, then destroy session and handle user as "not logged in"
        if (!isset($_SESSION['user_logged_in'])) {
            
            Session::destroy();
            // route user to login page
            header('location: ' . URL . 'login');            
            
        }
    }
      
    /**
     * Trying to login with cookie.
     * This has been build according to the guidance in this StackOverflow answer:
     * @see http://stackoverflow.com/a/17266448/1114320
     * @return boolean
     */
    public static function loginWithCookie() {
        
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
        
        if ($cookie) {

            list ($user_id, $token, $hash) = explode(':', $cookie);

            if ($hash !== hash('sha256', $user_id . ':' . $token)) {
                return false;
            }

            // do not log in when token is empty
            if (empty($token)) {
                return false;
            }
            
            // TODO: put this into a controller/model (which is difficult as the cookie stuff needs to be done
            // before any regular controller-action-calls)

            // create new database connection
            $db = new Database();
                    
            // get real token from database (and all other data)
            $sth = $db->prepare("SELECT user_id, 
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
                                   AND user_rememberme_token = :user_rememberme_token");
            $sth->execute(array(':user_id' => $user_id, ':user_rememberme_token' => $token));

            $count =  $sth->rowCount();
            if ($count == 1) {
                
                // fetch one row (we only have one result)
                $result = $sth->fetch();                
                
                // login
                Session::init();
                Session::set('user_logged_in', true);
                Session::set('user_id', $result->user_id);
                Session::set('user_name', $result->user_name);
                Session::set('user_email', $result->user_email);
                Session::set('user_account_type', $result->user_account_type);   
                
                if ($result->user_has_avatar) {
                    $avatar_path = URL . AVATAR_PATH . $result->user_id . '.jpg';
                    Session::set('user_avatar_file', $avatar_path);
                }  

                // call the setGravatarImageUrl() method which writes gravatar urls into the session
                Auth::setGravatarImageUrl($result->user_email);
                
                return true;
                
            } else {
                
                return false;
            }
            
        } else {
        
            return false;            
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
    public static function setGravatarImageUrl($email, $s = 44, $d = 'mm', $r = 'pg', $atts = array() ) {
        
        // TODO: why is this set when it's more a get ?
        
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
    
}
