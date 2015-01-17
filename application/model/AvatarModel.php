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

		// save it as a .jpg file with our $destination_filename parameter
		imagejpeg($thumb, $destination, $quality);

		// delete "working copy"
		imagedestroy($thumb);

		if (file_exists($destination)) {
			return true;
		}
		// default return
		return false;
	}
}
