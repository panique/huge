<?php

/**
 * Class AccountTypeModel
 *
 * This class contains everything that is related to up- and downgrading accounts.
 */
class AccountTypeModel
{
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
			Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL'));
			return true;
		}

		// default return
		Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_UPGRADE_FAILED'));
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
			Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL'));
			return true;
		}

		// default return
		Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_DOWNGRADE_FAILED'));
		return false;
	}

}