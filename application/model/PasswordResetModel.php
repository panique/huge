<?php

class PasswordResetModel
{
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
		$token_set = PasswordResetModel::setPasswordResetDatabaseToken($result->user_name, $user_password_reset_hash, $temporary_timestamp);
		if (!$token_set) {
			return false;
		}

		// ... and send a mail to the user, containing a link with username and token hash string
		$mail_sent = PasswordResetModel::sendPasswordResetMail($result->user_name, $user_password_reset_hash, $result->user_email);
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
}