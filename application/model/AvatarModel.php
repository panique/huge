<?php

class AvatarModel
{
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
		       '?s=' . Config::get('AVATAR_SIZE') . '&d=' . Config::get('GRAVATAR_DEFAULT_IMAGESET') . '&r=' . Config::get('GRAVATAR_RATING');
	}

	/**
	 * Gets the user's avatar file path
	 * @param int $user_has_avatar Marker from database
	 * @param int $user_id User's id
	 * @return string Avatar file path
	 */
	public static function getPublicAvatarFilePathOfUser($user_has_avatar, $user_id)
	{
		if ($user_has_avatar) {
			return Config::get('URL') . Config::get('PATH_AVATARS_PUBLIC') . $user_id . '.jpg';
		}

		return Config::get('URL') . Config::get('PATH_AVATARS_PUBLIC') . Config::get('AVATAR_DEFAULT_IMAGE');
	}

	/**
	 * Gets the user's avatar file path
	 * @param $user_id integer The user's id
	 * @return string avatar picture path
	 */
	public static function getPublicUserAvatarFilePathByUserId($user_id)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT user_has_avatar FROM users WHERE user_id = :user_id LIMIT 1");
		$query->execute(array(':user_id' => $user_id));

		if ($query->fetch()->user_has_avatar) {
			return Config::get('URL') . Config::get('PATH_AVATARS_PUBLIC') . $user_id . '.jpg';
		}

		return Config::get('URL') . Config::get('PATH_AVATARS_PUBLIC') . Config::get('AVATAR_DEFAULT_IMAGE');
	}

	/**
	 * Create an avatar picture (and checks all necessary things too)
	 * TODO decouple
	 * TODO total rebuild
	 */
	public static function createAvatar()
	{
		// check avatar folder writing rights, check if upload fits all rules
		if (AvatarModel::isAvatarFolderWritable() AND AvatarModel::validateImageFile()) {

			// create a jpg file in the avatar folder, write marker to database
			$target_file_path = Config::get('PATH_AVATARS') . Session::get('user_id');
			AvatarModel::resizeAvatarImage($_FILES['avatar_file']['tmp_name'], $target_file_path, Config::get('AVATAR_SIZE'), Config::get('AVATAR_SIZE'), Config::get('AVATAR_JPEG_QUALITY'));
			AvatarModel::writeAvatarToDatabase(Session::get('user_id'));
			Session::set('user_avatar_file', AvatarModel::getPublicUserAvatarFilePathByUserId(Session::get('user_id')));
			Session::add('feedback_positive', Text::get('FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL'));
		}
	}

	/**
	 * Checks if the avatar folder exists and is writable
	 *
	 * @return bool success status
	 */
	public static function isAvatarFolderWritable()
	{
		if (is_dir(Config::get('PATH_AVATARS')) AND is_writable(Config::get('PATH_AVATARS'))) {
			return true;
		}

		Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE'));
		return false;
	}

	/**
	 * Validates the image
	 * TODO totally decouple
	 *
	 * @return bool
	 */
	public static function validateImageFile()
	{
		if (!isset($_FILES['avatar_file'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
			return false;
		}

		if ($_FILES['avatar_file']['size'] > 5000000) {
			// if input file too big (>5MB)
			Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
			return false;
		}

		// get the image width, height and mime type
		$image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

		// if input file too small
		if ($image_proportions[0] < Config::get('AVATAR_SIZE') OR $image_proportions[1] < Config::get('AVATAR_SIZE')) {
			Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_SMALL'));
			return false;
		}

		if (!($image_proportions['mime'] == 'image/jpeg')) {
			Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
			return false;
		}

		return true;
	}

	/**
	 * Writes marker to database, saying user has an avatar now
	 *
	 * @param $user_id
	 */
	public static function writeAvatarToDatabase($user_id)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("UPDATE users SET user_has_avatar = TRUE WHERE user_id = :user_id LIMIT 1");
		$query->execute(array(':user_id' => $user_id));
	}

	/**
	 * Resize avatar image (while keeping aspect ratio and cropping it off sexy)
	 *
	 * TROUBLESHOOTING: You don't see the new image ? Press F5 or CTRL-F5 to refresh browser cache.
	 *
	 * @param string $source_image The location to the original raw image.
	 * @param string $destination The location to save the new image.
	 * @param int $final_width The desired width of the new image
	 * @param int $final_height The desired height of the new image.
	 * @param int $quality The quality of the JPG to produce 1 - 100
	 *
	 * TODO currently we just allow .jpg
	 *
	 * @return bool success state
	 */
	public static function resizeAvatarImage($source_image, $destination, $final_width = 44, $final_height = 44, $quality = 85)
	{
		list($width, $height) = getimagesize($source_image);

		if (!$width || !$height) {
			return false;
		}

		//saving the image into memory (for manipulation with GD Library)
		$myImage = imagecreatefromjpeg($source_image);

		// calculating the part of the image to use for thumbnail
		if ($width > $height) {
			$y = 0;
			$x = ($width - $height) / 2;
			$smallestSide = $height;
		} else {
			$x = 0;
			$y = ($height - $width) / 2;
			$smallestSide = $width;
		}

		// copying the part into thumbnail, maybe edit this for square avatars
		$thumb = imagecreatetruecolor($final_width, $final_height);
		imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $final_width, $final_height, $smallestSide, $smallestSide);

		// add '.jpg' to file path, save it as a .jpg file with our $destination_filename parameter
		$destination .= '.jpg';
		imagejpeg($thumb, $destination, $quality);

		// delete "working copy"
		imagedestroy($thumb);

		if (file_exists($destination)) {
			return true;
		}
		// default return
		return false;
	}

    /**
     * Delete a user's avatar
     *
     * @param int $userId
     * @return bool success
     */
    public static function deleteAvatar($userId)
    {
        if (!ctype_digit($userId)) {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_FAILED"));
            return false;
        }

        // try to delete image, but still go on regardless of file deletion result
        self::deleteAvatarImageFile($userId);

        $database = DatabaseFactory::getFactory()->getConnection();

        $sth = $database->prepare("UPDATE users SET user_has_avatar = 0 WHERE user_id = :user_id LIMIT 1");
        $sth->bindValue(":user_id", (int)$userId, PDO::PARAM_INT);
        $sth->execute();

        if ($sth->rowCount() == 1) {
            Session::set('user_avatar_file', self::getPublicUserAvatarFilePathByUserId($userId));
            Session::add("feedback_positive", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_SUCCESSFUL"));
            return true;
        } else {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_FAILED"));
            return false;
        }
    }

    /**
     * Removes the avatar image file from the filesystem
     *
     * @param $userId
     * @return bool
     */
    public static function deleteAvatarImageFile($userId)
    {
        // Check if file exists
        if (!file_exists(Config::get('PATH_AVATARS') . $userId . ".jpg")) {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_NO_FILE"));
            return false;
        }

        // Delete avatar file
        if (!unlink(Config::get('PATH_AVATARS') . $userId . ".jpg")) {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_FAILED"));
            return false;
        }

        return true;
    }
}
