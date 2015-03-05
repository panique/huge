<?php

/**
 * UserModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class AdminModel
{

	public static function setAccountSusspensionAndDeletetionStatus($suspension, $softDelete, $userID)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		if ($suspension > 1) {
			$suspensionTimeStamp = time() + ($suspension * 60 * 60 * 24);
		} else {
			$suspensionTimeStamp = 0;
		}

		if ($softDelete == "on") {
			$delete = 1;
		} else {
			$delete = 0;
		}

		$query = $database->prepare("UPDATE users SET user_suspension_timestamp = :suspensionTime, user_deleted = :deleted  WHERE user_id = :user_id LIMIT 1");
		$query->execute(array(
			':suspensionTime' => $suspensionTimeStamp,
			':deleted' => $delete,
			':user_id' => $userID
		));

		if ($query->rowCount() == 1) {
			Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_SUSPENSION_DELETEION_STATUS'));
			return true;
		}
	}


}

//user_suspension_timestamp
//user_deleted