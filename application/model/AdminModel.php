<?php

/**
 * Handles all data manipulation of the admin part
 */
class AdminModel
{
	public static function setAccountSuspensionAndDeletionStatus($suspensionInDays, $softDelete, $userId)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		if ($suspensionInDays > 0) {
			$suspensionTime = time() + ($suspensionInDays * 60 * 60 * 24);
		} else {
			$suspensionTime = null;
		}

        // FYI "on" is what a checkbox delivers by default when submitted. Didn't know that for a long time :)
		if ($softDelete == "on") {
			$delete = 1;
		} else {
			$delete = 0;
		}

		$query = $database->prepare("UPDATE users SET user_suspension_timestamp = :user_suspension_timestamp, user_deleted = :user_deleted  WHERE user_id = :user_id LIMIT 1");
		$query->execute(array(
			':user_suspension_timestamp' => $suspensionTime,
			':user_deleted' => $delete,
			':user_id' => $userId
		));

		if ($query->rowCount() == 1) {
			Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_SUSPENSION_DELETION_STATUS'));
			return true;
		}
	}
}
