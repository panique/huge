<?php

/**
 * UserModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class UserModel
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
    public static function getPublicProfilesOfAllUsers()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar FROM users";
        $query = $database->prepare($sql);
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
                    UserModel::getGravatarLinkByEmail($user->user_email);
            } else {
                $all_users_profiles[$user->user_id]->user_avatar_link =
                    UserModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
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
    public static function getPublicProfileOfUser($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar
                FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        $user = $query->fetch();

        if ($query->rowCount() == 1) {
            if (USE_GRAVATAR) {
                $user->user_avatar_link = UserModel::getGravatarLinkByEmail($user->user_email);
            } else {
                $user->user_avatar_link = UserModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
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
    public static function getGravatarLinkByEmail($email)
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
    public static function getPublicAvatarFilePathOfUser($user_has_avatar, $user_id)
    {
        if ($user_has_avatar) {
            return URL . PATH_AVATARS_PUBLIC . $user_id . '.jpg';
        }

        // default
        return URL . PATH_AVATARS_PUBLIC . AVATAR_DEFAULT_IMAGE;
    }

    /**
     * Upgrades the user's account (for DEFAULT and FACEBOOK users)
     * Currently it's just the field user_account_type in the database that
     * can be 1 or 2 (maybe "basic" or "premium"). In this basic method we
     * simply increase this value to emulate an account upgrade.
     * Put some more complex stuff in here, maybe a pay-process or whatever you like.
     */
    public static function changeAccountTypeUpgrade()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_account_type = 2 WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            // set account type in session to 2
            Session::set('user_account_type', 2);
            Session::add('feedback_positive', FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL);
            return true;
        }

        // default return
        Session::add('feedback_negative', FEEDBACK_ACCOUNT_UPGRADE_FAILED);
        return false;
    }

    /**
     * Downgrades the user's account (for DEFAULT and FACEBOOK users)
     * Currently it's just the field user_account_type in the database that
     * can be 1 or 2 (maybe "basic" or "premium"). In this basic method we
     * simply decrease this value to emulate an account downgrade.
     * Put some more complex stuff in here, maybe a pay-process or whatever you like.
     */
    public static function changeAccountTypeDowngrade()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_account_type = 1 WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            // set account type in session to 1
            Session::set('user_account_type', 1);
            Session::add('feedback_positive', FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL);
            return true;
        }

        // default return
        Session::add('feedback_negative', FEEDBACK_ACCOUNT_DOWNGRADE_FAILED);
        return false;
    }

    /**
     * @param $user_name
     * @param $user_password_hash
     * @param $user_password_reset_hash
     *
     * @return bool
     */
    public static function saveNewUserPassword($user_name, $user_password_hash, $user_password_reset_hash)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users
                   SET user_password_hash = :user_password_hash,
                       user_password_reset_hash = NULL,
                       user_password_reset_timestamp = NULL
                 WHERE user_name = :user_name
                       AND user_password_reset_hash = :user_password_reset_hash
                       AND user_provider_type = :user_provider_type
                 LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':user_password_hash' => $user_password_hash, ':user_name' => $user_name,
            ':user_password_reset_hash' => $user_password_reset_hash, ':user_provider_type' => 'DEFAULT'
        ));

        // if successful
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Set the new password (for DEFAULT user, FACEBOOK-users don't have a password)
     * Please note: At this point the user has already pre-verified via verifyPasswordReset() (within one hour),
     * so we don't need to check again for the 60min-limit here. In this method we authenticate
     * via username & password-reset-hash from (hidden) form fields.
     * @param $user_name
     * @param $user_password_reset_hash
     * @param $user_password_new
     * @param $user_password_repeat
     *
     * @return bool success state of the password reset
     */
    public static function setNewPassword($user_name, $user_password_reset_hash, $user_password_new, $user_password_repeat)
    {
        if (empty($user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_FIELD_EMPTY);
            return false;
        }
        if (empty($user_password_reset_hash)) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_TOKEN_MISSING);
            return false;
        }
        if (empty($user_password_new)) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_FIELD_EMPTY);
            return false;
        }
        if (empty($user_password_repeat)) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_FIELD_EMPTY);
            return false;
        }
        if ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_REPEAT_WRONG);
            return false;
        }
        if (strlen($user_password_new) < 6) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_TOO_SHORT);
            return false;
        }

        // crypt the user's password with the PHP 5.5+'s password_hash() function, result is a 60 character hash string
        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);

        // write user's new password hash into database, reset user_password_reset_hash
        if (UserModel::saveNewUserPassword($user_name, $user_password_hash, $user_password_reset_hash)) {
            Session::add('feedback_positive', FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL);
            return true;
        }

        // default return
        Session::add('feedback_negative', FEEDBACK_PASSWORD_CHANGE_FAILED);
        return false;
    }
}
