<?php

/**
 * Class AccountTypeModel
 *
 * This class contains everything that is related to up- and downgrading accounts.
 */
class AccountTypeModel
{
	/**
	 * Upgrades / downgrades the user's account. Currently it's just the field user_account_type in the database that
	 * can be 1 or 2 (maybe "basic" or "premium"). Put some more complex stuff in here, maybe a pay-process or whatever
	 * you like.
	 *
	 * @param $type
	 *
	 * @return bool
	 */
	public static function changeAccountType($type)
	{
		// this is error-prone, let's rewrite it
		if (!$type OR ($type !== 1 AND $type !== 2)) {
			return false;
		}

		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("UPDATE users SET user_account_type = :new_type WHERE user_id = :user_id LIMIT 1");
		$query->execute(array(
			':new_type' => $type,
			':user_id' => Session::get('user_id')
		));

		// feedback message
		$feedback = "";

		if ($query->rowCount() == 1) {

			// set account type in session
			Session::set('user_account_type', $type);

			switch ($type) {
				case 1:
					$feedback = Text::get('FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL');
					break;
				case 2:
					$feedback = Text::get('FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL');
					break;
			}

			Session::add('feedback_positive', $feedback);
			return true;
		}

		// default return
		Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_TYPE_CHANGE_FAILED'));
		return false;
	}

}