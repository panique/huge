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
}
