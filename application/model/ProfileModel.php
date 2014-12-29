<?php

/**
 * ProfileModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class ProfileModel
{
    /** @var Database $database The database (surprise!) */
    private $database;

    /**
     * Constructor, expects a Database connection
     * @param Database $database The Database object
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Gets an array that contains all the users in the database. The array's keys are the user ids.
     * Each array element is an object, containing a specific user's data.
     * @return array The profiles of all users
     */
    public function getPublicProfilesOfAllUsers()
    {
        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar FROM users";
        $query = $this->database->prepare($sql);
        $query->execute();

        $all_users_profiles = array();

        foreach ($query->fetchAll() as $user) {
            // a new object for every user. This is eventually not really optimal when it comes
            // to performance, but it fits the view style better
            $all_users_profiles[$user->user_id] = new stdClass();
            $all_users_profiles[$user->user_id]->user_id = $user->user_id;
            $all_users_profiles[$user->user_id]->user_name = $user->user_name;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email;

            if (USE_GRAVATAR) {
                $all_users_profiles[$user->user_id]->user_avatar_link =
                    $this->getGravatarLinkByEmail($user->user_email);
            } else {
                $all_users_profiles[$user->user_id]->user_avatar_link =
                    $this->getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
            }

            $all_users_profiles[$user->user_id]->user_active = $user->user_active;
        }

        return $all_users_profiles;
    }

    /**
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return object/null The selected user's profile
     */
    public function getPublicProfileOfUser($user_id)
    {
        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar
                FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $this->database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        $user = $query->fetch();

        if ($query->rowCount() == 1) {
            if (USE_GRAVATAR) {
                $user->user_avatar_link = $this->getGravatarLinkByEmail($user->user_email);
            } else {
                $user->user_avatar_link = $this->getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
            }
        } else {
            Session::add('feedback_negative', FEEDBACK_USER_DOES_NOT_EXIST);
        }

        return $user;
    }

    /**
     * Gets a gravatar image link from given email address
     *
     * Gravatar is the #1 (free) provider for email address based global avatar hosting.
     * The URL (or image) returns always a .jpg file ! For deeper info on the different parameter possibilities:
     * @see http://gravatar.com/site/implement/images/
     * @source http://gravatar.com/site/implement/images/php/
     *
     * This method will return something like http://www.gravatar.com/avatar/79e2e5b48aec07710c08d50?s=80&d=mm&r=g
     * Note: the url does NOT have something like ".jpg" ! It works without.
     *
     * Set the configs inside the application/config/ files.
     *
     * @param string $email The email address
     * @return string
     */
    public function getGravatarLinkByEmail($email)
    {
        return 'http://www.gravatar.com/avatar/' .
               md5( strtolower( trim( $email ) ) ) .
               '?s=' . AVATAR_SIZE . '&d=' . GRAVATAR_DEFAULT_IMAGESET . '&r=' . GRAVATAR_RATING;
    }

    /**
     * Gets the user's avatar file path
     * @param int $user_has_avatar Marker from database
     * @param int $user_id User's id
     * @return string/null Avatar file path
     */
    public function getPublicAvatarFilePathOfUser($user_has_avatar, $user_id)
    {
        if ($user_has_avatar) {
            return URL . PATH_AVATARS_PUBLIC . $user_id . '.jpg';
        }

        // default
        return URL . PATH_AVATARS_PUBLIC . AVATAR_DEFAULT_IMAGE;
    }
}
