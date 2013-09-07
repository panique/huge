<?php

/**
 * A simple, clean and secure PHP Login Script
 *
 * ADVANCED VERSION
 * (check the website / github / facebook for other versions)
 *
 * A simple PHP Login Script.
 * Uses PHP SESSIONS, modern password-hashing and salting
 * and gives the basic functions a proper login system needs.
 *
 * @package php-login
 * @author Panique <panique@web.de>
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// load php-login components
require_once('php-login.php');

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

// include html header and display php-login message/error
include('includes/header.php');

// the user just came to our page by the URL provided in the password-reset-mail
// and all data is valid, so we show the type-your-new-password form
if ($login->passwordResetLinkIsValid() == true) { ?>             
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

// the user has just successfully entered a new password
// no data from a password-reset-mail has been provided, so we simply show the request-a-password-reset form
} else if ($login->passwordResetWasSuccessful() != true) { ?>
	<form method="post" action="password_reset.php" name="password_reset_form">
		<label for="user_name"><?php echo $phplogin_lang['Password reset request']; ?></label>
		<input id="user_name" type="text" name="user_name" required />
		<input type="submit" name="request_password_reset" value="<?php echo $phplogin_lang['Reset my password']; ?>" />
	</form>
<?php } ?>
<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>

<?php

// include html footer
include('includes/footer.php');
