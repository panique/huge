<?php

class RegistrationModel
{
	/**
	 * handles the entire registration process for DEFAULT users (not for people who register with
	 * 3rd party services, like facebook) and creates a new user in the database if everything is fine
	 *
	 * TODO rewrite / modernize
	 *
	 * @return boolean Gives back the success status of the registration
	 */
	public static function registerNewUser()
	{
		// perform all necessary form checks
		if (!CaptchaModel::checkCaptcha(Request::post('captcha'))) {
			$_SESSION["feedback_negative"][] = FEEDBACK_CAPTCHA_WRONG;
		} elseif (empty($_POST['user_name'])) {
			$_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_FIELD_EMPTY;
		} elseif (empty($_POST['user_password_new']) OR empty($_POST['user_password_repeat'])) {
			$_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_FIELD_EMPTY;
		} elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
			$_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_REPEAT_WRONG;
		} elseif (strlen($_POST['user_password_new']) < 6) {
			$_SESSION["feedback_negative"][] = FEEDBACK_PASSWORD_TOO_SHORT;
		} elseif (strlen($_POST['user_name']) > 64 OR strlen($_POST['user_name']) < 2) {
			$_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG;
		} elseif (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $_POST['user_name'])) {
			$_SESSION["feedback_negative"][] = FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN;
		} elseif (empty($_POST['user_email'])) {
			$_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_FIELD_EMPTY;
		} elseif (strlen($_POST['user_email']) > 254) {
			// @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
			$_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_TOO_LONG;
		} elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
			$_SESSION["feedback_negative"][] = FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN;
		} elseif (!empty($_POST['user_name'])
		          AND strlen($_POST['user_name']) <= 64
		              AND strlen($_POST['user_name']) >= 2
		                  AND preg_match('/^[a-zA-Z0-9]{2,64}$/', $_POST['user_name'])
		                      AND !empty($_POST['user_email'])
		                          AND strlen($_POST['user_email']) <= 254
		                              AND filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
		                                  AND !empty($_POST['user_password_new'])
		                                      AND !empty($_POST['user_password_repeat'])
		                                          AND ($_POST['user_password_new'] === $_POST['user_password_repeat'])) {

			// clean the input
			$user_name = strip_tags($_POST['user_name']);
			$user_email = strip_tags($_POST['user_email']);

			// crypt the password with the PHP 5.5's password_hash() function, results in a 60 character hash string.
			// @see php.net/manual/en/function.password-hash.php for more, especially for potential options
			$user_password_hash = password_hash($_POST['user_password_new'], PASSWORD_DEFAULT);

			// check if username already exists
			if (UserModel::doesUsernameAlreadyExist($user_name)) {
				Session::add('feedback_negative', FEEDBACK_USERNAME_ALREADY_TAKEN);
				return false;
			}

			// check if email already exists
			if (UserModel::doesEmailAlreadyExist($user_email)) {
				Session::add('feedback_negative', FEEDBACK_USER_EMAIL_ALREADY_TAKEN);
				return false;
			}

			// generate random hash for email verification (40 char string)
			$user_activation_hash = sha1(uniqid(mt_rand(), true));
			// generate integer-timestamp for saving of account-creating date
			$user_creation_timestamp = time();

			// write user data to database
			if (!RegistrationModel::writeNewUserToDatabase($user_name, $user_password_hash, $user_email, $user_creation_timestamp, $user_activation_hash)) {
				Session::add('feedback_negative', FEEDBACK_ACCOUNT_CREATION_FAILED);
			}

			// get user_id of the user that has been created, to keep things clean we DON'T use lastInsertId() here
			$user_id = UserModel::getUserIdByUsername($user_name);

			if (!$user_id) {
				Session::add('feedback_negative', FEEDBACK_UNKNOWN_ERROR);
				return false;
			}

			// send verification email
			if (RegistrationModel::sendVerificationEmail($user_id, $user_email, $user_activation_hash)) {
				Session::add('feedback_positive', FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED);
				return true;
			} else {
				// if verification email sending failed: instantly delete the user
				RegistrationModel::rollbackRegistrationByUserId($user_id);
				Session::add('feedback_negative', FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED);
				return false;
			}
		}

		// default fallback
		Session::add('feedback_negative', FEEDBACK_UNKNOWN_ERROR);
		return false;
	}

	/**
	 * Writes the new user's data to the database
	 *
	 * @param $user_name
	 * @param $user_password_hash
	 * @param $user_email
	 * @param $user_creation_timestamp
	 * @param $user_activation_hash
	 *
	 * @return bool
	 */
	public static function writeNewUserToDatabase($user_name, $user_password_hash, $user_email, $user_creation_timestamp, $user_activation_hash)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		// write new users data into database
		$sql = "INSERT INTO users (user_name, user_password_hash, user_email, user_creation_timestamp, user_activation_hash, user_provider_type)
                    VALUES (:user_name, :user_password_hash, :user_email, :user_creation_timestamp, :user_activation_hash, :user_provider_type)";
		$query = $database->prepare($sql);
		$query->execute(array(':user_name' => $user_name,
		                      ':user_password_hash' => $user_password_hash,
		                      ':user_email' => $user_email,
		                      ':user_creation_timestamp' => $user_creation_timestamp,
		                      ':user_activation_hash' => $user_activation_hash,
		                      ':user_provider_type' => 'DEFAULT'));
		$count =  $query->rowCount();
		if ($count == 1) {
			return true;
		}

		return false;
	}

	/**
	 * Deletes the user from users table. Currently used to rollback a registration when verification mail sending
	 * was not successful.
	 *
	 * @param $user_id
	 */
	public static function rollbackRegistrationByUserId($user_id)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("DELETE FROM users WHERE user_id = :user_id");
		$query->execute(array(':user_id' => $user_id));
	}

	/**
	 * Sends the verification email (to confirm the account)
	 *
	 * @param int $user_id user's id
	 * @param string $user_email user's email
	 * @param string $user_activation_hash user's mail verification hash string
	 *
	 * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
	 */
	public static function sendVerificationEmail($user_id, $user_email, $user_activation_hash)
	{
		// create email body
		$body = EMAIL_VERIFICATION_CONTENT . EMAIL_VERIFICATION_URL . '/' . urlencode($user_id) . '/'
		        . urlencode($user_activation_hash);

		// create instance of Mail class, try sending and check
		$mail = new Mail;
		$mail_sent = $mail->sendMail(
			$user_email, EMAIL_VERIFICATION_FROM_EMAIL, EMAIL_VERIFICATION_FROM_NAME, EMAIL_VERIFICATION_SUBJECT, $body
		);

		if ($mail_sent) {
			Session::add('feedback_positive', FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL);
			return true;
		}

		Session::add('feedback_negative', FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR . $mail->getError() );
		return false;
	}

	/**
	 * checks the email/verification code combination and set the user's activation status to true in the database
	 *
	 * @param int $user_id user id
	 * @param string $user_activation_verification_code verification token
	 *
	 * @return bool success status
	 */
	public static function verifyNewUser($user_id, $user_activation_verification_code)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$sql = "UPDATE users SET user_active = 1, user_activation_hash = NULL
                WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash LIMIT 1";
		$query = $database->prepare($sql);
		$query->execute(array(':user_id' => $user_id, ':user_activation_hash' => $user_activation_verification_code));

		if ($query->rowCount() == 1) {
			Session::add('feedback_positive', FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL);
			return true;
		}

		Session::add('feedback_negative', FEEDBACK_ACCOUNT_ACTIVATION_FAILED);
		return false;
	}
}
