<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP-login</title>
</head>
<body>
<?php

// show negative messages
if ($login->errors) {
    foreach ($login->errors as $error) {
        echo $error;
    }
}

// show positive messages
if ($login->messages) {
    foreach ($login->messages as $message) {
        echo $message;
    }
}

// the user just came to our page by the URL provided in the password-reset-mail
// and all data is valid, so we show the type-your-new-password form
if ($login->passwordResetLinkIsValid() == true) {
?>             
<form method="post" action="password_reset.php" name="new_password_form">
	<input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
	<input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>' />

	<label for="reset_input_password_new"><?php echo $phplogin_lang['New password']; ?></label>
	<input id="reset_input_password_new" class="reset_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />

	<label for="reset_input_password_repeat"><?php echo $phplogin_lang['Repeat new password']; ?></label>
	<input id="reset_input_password_repeat" class="reset_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
	<input type="submit"  name="submit_new_password" value="<?php echo $phplogin_lang['Submit new password']; ?>" />
</form>
<?php
// no data from a password-reset-mail has been provided, so we simply show the request-a-password-reset form
} else {
?>
<form method="post" action="password_reset.php" name="password_reset_form">
	<label for="password_reset_input_username"><?php echo $phplogin_lang['Password reset request']; ?></label>
	<input id="password_reset_input_username" class="password_reset_input" type="text" name="user_name" required />
	<input type="submit" name="request_password_reset" value="<?php echo $phplogin_lang['Reset my password']; ?>" />
</form>
<?php
}
?>
<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>
</body>
</html>
