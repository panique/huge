<?php
// include html header and display php-login message/error
include('header.php');

// the user just came to our page by the URL provided in the password-reset-mail
// and all data is valid, so we show the type-your-new-password form
if ($login->passwordResetLinkIsValid() == true) {
?>             
<form method="post" action="password_reset.php" name="new_password_form">
	<input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
	<input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>' />

	<label for="user_password_new"><?php echo $phplogin_lang['New password']; ?></label>
	<input id="user_password_new" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />

	<label for="user_password_repeat"><?php echo $phplogin_lang['Repeat new password']; ?></label>
	<input id="user_password_repeat" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
	<input type="submit" name="submit_new_password" value="<?php echo $phplogin_lang['Submit new password']; ?>" />
</form>
<?php
// no data from a password-reset-mail has been provided, so we simply show the request-a-password-reset form
} else {
?>
<form method="post" action="password_reset.php" name="password_reset_form">
	<label for="user_name"><?php echo $phplogin_lang['Password reset request']; ?></label>
	<input id="user_name" type="text" name="user_name" required />
	<input type="submit" name="request_password_reset" value="<?php echo $phplogin_lang['Reset my password']; ?>" />
</form>
<?php
}
?>
<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>

<?php
// include html footer
include('footer.php');
