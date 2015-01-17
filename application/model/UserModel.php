<?php

/**
 * UserModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class UserModel
{
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
                    AvatarModel::getGravatarLinkByEmail($user->user_email);
            } else {
                $all_users_profiles[$user->user_id]->user_avatar_link =
                    AvatarModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
            }

            $all_users_profiles[$user->user_id]->user_active = $user->user_active;
        }

        return $all_users_profiles;
    }

    /**
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return mixed The selected user's profile
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
                $user->user_avatar_link = AvatarModel::getGravatarLinkByEmail($user->user_email);
            } else {
                $user->user_avatar_link = AvatarModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
            }
        } else {
            Session::add('feedback_negative', FEEDBACK_USER_DOES_NOT_EXIST);
        }

        return $user;
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

    /**
     * @param $user_name_or_email
     *
     * @return mixed
     */
    public static function getUserDataByUserNameOrEmail($user_name_or_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id, user_name, user_email FROM users
                                     WHERE (user_name = :user_name_or_email OR user_email = :user_name_or_email)
                                           AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_name_or_email' => $user_name_or_email, ':provider_type' => 'DEFAULT'));

        return $query->fetch();
    }

    /**
     * Verifies the password reset request via the verification hash token (that's only valid for one hour)
     * @param string $user_name Username
     * @param string $verification_code Hash token
     * @return bool Success status
     */
    public static function verifyPasswordReset($user_name, $verification_code)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // check if user-provided username + verification code combination exists
        $sql = "SELECT user_id, user_password_reset_timestamp
                  FROM users
                 WHERE user_name = :user_name
                       AND user_password_reset_hash = :user_password_reset_hash
                       AND user_provider_type = :user_provider_type
                 LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':user_password_reset_hash' => $verification_code, ':user_name' => $user_name,
            ':user_provider_type' => 'DEFAULT'
        ));

        // if this user with exactly this verification hash code does NOT exist
        if ($query->rowCount() != 1) {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST);
            return false;
        }

        // get result row (as an object)
        $result_user_row = $query->fetch();

        // 3600 seconds are 1 hour
        $timestamp_one_hour_ago = time() - 3600;

        // if password reset request was sent within the last hour (this timeout is for security reasons)
        if ($result_user_row->user_password_reset_timestamp > $timestamp_one_hour_ago) {
            // verification was successful
            Session::add('feedback_positive', FEEDBACK_PASSWORD_RESET_LINK_VALID);
            return true;
        } else {
            Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_LINK_EXPIRED);
            return false;
        }
    }

    public static function doesUsernameAlreadyExist($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $user_name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public static function doesEmailAlreadyExist($user_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_email = :user_email LIMIT 1");
        $query->execute(array(':user_email' => $user_email));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public static function saveNewUserName($user_id, $new_user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_name = :user_name WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_name' => $new_user_name, ':user_id' => $user_id));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public static function saveNewEmailAddress($user_id, $new_user_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_email = :user_email WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_email' => $new_user_email, ':user_id' => $user_id));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }

    /**
     * Edit the user's name, provided in the editing form
     *
     * @param $new_user_name string The new username
     *
     * @return bool success status
     */
    public static function editUserName($new_user_name)
    {
        // new username provided ?
        if (empty($new_user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_FIELD_EMPTY);
            return false;
        }

        // new username same as old one ?
        if ($new_user_name == Session::get('user_name')) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_SAME_AS_OLD_ONE);
            return false;
        }

        // username cannot be empty and must be azAZ09 and 2-64 characters
        if (!preg_match("/^[a-zA-Z0-9]{2,64}$/", $new_user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN);
            return false;
        }

        // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
        $new_user_name = substr(strip_tags($new_user_name), 0, 64);

        // check if new username already exists
        if (UserModel::doesUsernameAlreadyExist($new_user_name)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_ALREADY_TAKEN);
            return false;
        }

        $status_of_action = UserModel::saveNewUserName(Session::get('user_id'), $new_user_name);
        if ($status_of_action) {
            Session::set('user_name', $new_user_name);
            Session::add('feedback_positive', FEEDBACK_USERNAME_CHANGE_SUCCESSFUL);
            return true;
        }

        // default fallback
        Session::add('feedback_negative', FEEDBACK_UNKNOWN_ERROR);
        return false;
    }

    /**
     * Edit the user's email
     *
     * @param $new_user_email
     *
     * @return bool success status
     */
    public static function editUserEmail($new_user_email)
    {
        // email provided ?
        if (empty($new_user_email)) {
            Session::add('feedback_negative', FEEDBACK_EMAIL_FIELD_EMPTY);
            return false;
        }

        // check if new email is same like the old one
        if ($new_user_email == Session::get('user_email')) {
            Session::add('feedback_negative', FEEDBACK_EMAIL_SAME_AS_OLD_ONE);
            return false;
        }

        // user's email must be in valid email format
        if (!filter_var($new_user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN);
            return false;
        }

        // cut email length (everything else is spam and should later be deleted)
        // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
        // TODO is this even necessary anymore as we use FILTER_VALIDATE_EMAIL above ?
        $new_user_email = substr(strip_tags($new_user_email), 0, 254);

        // check if user's email already exists
        if (UserModel::doesEmailAlreadyExist($new_user_email)) {
            Session::add('feedback_negative', FEEDBACK_USER_EMAIL_ALREADY_TAKEN);
            return false;
        }

        // write to database, if successful ...
        // ... then write new email to session, Gravatar too (as this relies to the user's email address)
        if (UserModel::saveNewEmailAddress(Session::get('user_id'), $new_user_email)) {
            Session::set('user_email', $new_user_email);
            Session::set('user_gravatar_image_url', AvatarModel::getGravatarLinkByEmail($new_user_email));
            Session::add('feedback_positive', FEEDBACK_EMAIL_CHANGE_SUCCESSFUL);
            return true;
        }

        Session::add('feedback_negative', FEEDBACK_UNKNOWN_ERROR);
        return false;
    }

    /**
     * Perform the necessary actions to send a password reset mail
     *
     * @param $user_name_or_email string Username or user's email
     *
     * @return bool success status
     */
    public static function requestPasswordReset($user_name_or_email)
    {
        if (empty($user_name_or_email)) {
            Session::add('feedback_negative', FEEDBACK_USERNAME_EMAIL_FIELD_EMPTY);
            return false;
        }

        // check if that username exists
        $result = UserModel::getUserDataByUserNameOrEmail($user_name_or_email);
        if (!$result) {
            Session::add('feedback_negative', FEEDBACK_USER_DOES_NOT_EXIST);
            return false;
        }

        // generate integer-timestamp (to see when exactly the user (or an attacker) requested the password reset mail)
        // generate random hash for email password reset verification (40 char string)
        $temporary_timestamp = time();
        $user_password_reset_hash = sha1(uniqid(mt_rand(), true));

        // set token (= a random hash string and a timestamp) into database ...
        $token_set = UserModel::setPasswordResetDatabaseToken($result->user_name, $user_password_reset_hash, $temporary_timestamp);
        if (!$token_set) {
            return false;
        }

        // ... and send a mail to the user, containing a link with username and token hash string
        $mail_sent = UserModel::sendPasswordResetMail($result->user_name, $user_password_reset_hash, $result->user_email);
        if ($mail_sent) {
            return true;
        }

        // default return
        return false;
    }

    /**
     * Set password reset token in database (for DEFAULT user accounts)
     *
     * @param string $user_name username
     * @param string $user_password_reset_hash password reset hash
     * @param int $temporary_timestamp timestamp
     *
     * @return bool success status
     */
    public static function setPasswordResetDatabaseToken($user_name, $user_password_reset_hash, $temporary_timestamp)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // this could be formatted better
        $sql = "UPDATE users
                SET user_password_reset_hash = :user_password_reset_hash,
                    user_password_reset_timestamp = :user_password_reset_timestamp
                WHERE user_name = :user_name AND user_provider_type = :provider_type
                LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':user_password_reset_hash' => $user_password_reset_hash, ':user_name' => $user_name,
            ':user_password_reset_timestamp' => $temporary_timestamp, ':provider_type' => 'DEFAULT'
        ));

        // check if exactly one row was successfully changed
        if ($query->rowCount() == 1) {
            return true;
        }

        // fallback
        Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_TOKEN_FAIL);
        return false;
    }

    /**
     * Send the password reset mail
     *
     * @param string $user_name username
     * @param string $user_password_reset_hash password reset hash
     * @param string $user_email user email
     *
     * @return bool success status
     */
    public static function sendPasswordResetMail($user_name, $user_password_reset_hash, $user_email)
    {
        // create email body
        $body = EMAIL_PASSWORD_RESET_CONTENT . ' ' . EMAIL_PASSWORD_RESET_URL . '/' . urlencode($user_name) . '/'
                . urlencode($user_password_reset_hash);

        // create instance of Mail class, try sending and check
        $mail = new Mail;
        $mail_sent = $mail->sendMail(
            $user_email, EMAIL_PASSWORD_RESET_FROM_EMAIL, EMAIL_PASSWORD_RESET_FROM_NAME, EMAIL_PASSWORD_RESET_SUBJECT, $body
        );

        if ($mail_sent) {
            Session::add('feedback_positive', FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL);
            return true;
        }

        Session::add('feedback_negative', FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR . $mail->getError() );
        return false;
    }

    /**
     * Gets the user's data
     *
     * @param $user_name string User's name
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserDataByUsername($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_password_hash, user_active, user_account_type,
                       user_failed_logins, user_last_failed_login
                  FROM users
                 WHERE (user_name = :user_name OR user_email = :user_name)
                       AND user_provider_type = :provider_type
                 LIMIT 1";
        $query = $database->prepare($sql);

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $query->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }
}
