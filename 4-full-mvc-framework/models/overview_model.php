<?php

class Overview_Model extends Model {
    
    public $errors = array();

    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets an array that contains all the users in the database
     * The array's keys are the user ids. Each array element is an object,
     * containing a specific user's data.
     * @return array
     */
    public function getAllUsersProfiles() {

        $sth = $this->db->prepare("SELECT user_id, user_name, user_email, user_active, user_has_avatar FROM users");
        $sth->execute();
        
        $all_users_profiles = array();

        foreach ($sth->fetchAll() as $user) {
            
            // a new object for every user. This is eventually not really optimal when it comes
            // to performance, but it fits the view style better
            $all_users_profiles[$user->user_id] = new stdClass();
            
            $all_users_profiles[$user->user_id]->user_id = $user->user_id; // hmm...
            $all_users_profiles[$user->user_id]->user_name = $user->user_name;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email; // be careful with public emails in real apps
            
            if (USE_GRAVATARS) {
                $all_users_profiles[$user->user_id]->user_avatar_link = $this->getGravatarLinkFromEmail($user->user_email);
            } else {
                $all_users_profiles[$user->user_id]->user_avatar_link = $this->getUserAvatarFilePath($user->user_has_avatar, $user->user_id);
            }
            
            $all_users_profiles[$user->user_id]->user_active = $user->user_active;
        }
        
        return $all_users_profiles;
    }    
    
    /**
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return object
     */
    public function getUserProfile($user_id) {

        $sth = $this->db->prepare("SELECT user_id, user_name, user_email, user_active, user_has_avatar FROM users WHERE user_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        
        $user = $sth->fetch();
        
        if (USE_GRAVATARS) {            
            $user->user_avatar_link = $this->getGravatarLinkFromEmail($user->user_email);
        } else {
            $user->user_avatar_link = $this->getUserAvatarFilePath($user->user_has_avatar, $user->user_id);
        }        
        
        return $user;
    }        
    
    /**
     * Gets a gravatar image link from given email adress
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
    public function getGravatarLinkFromEmail($email, $s = 44, $d = 'mm', $r = 'pg', $atts = array() ) {
        
        $gravatar_image_link = 'http://www.gravatar.com/avatar/';
        $gravatar_image_link .= md5( strtolower( trim( $email ) ) );
        $gravatar_image_link .= "?s=$s&d=$d&r=$r";
        
        // the image url (on gravatar servers), will return in something like
        // http://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=80&d=mm&r=g
        // note: the url does NOT have something like .jpg
        return $gravatar_image_link;
        
    }
    
    /**
     * Gets the user's avatar file path
     * @return string
     */
    public function getUserAvatarFilePath($user_has_avatar, $user_id) {
        
        if ($user_has_avatar) {
         
            return URL . AVATAR_PATH . $user_id . '.jpg';
            
        }
        
    }      
    
}
