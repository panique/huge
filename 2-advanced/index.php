<?php

/**
 * A simple, clean and secure PHP Login Script
 *
 * ADVANCED VERSION
 * (check the website / github / facebook for other versions)
 *
 * A simple PHP Login Script.
 * Uses PHP SESSIONS, modern SHA512-password-hashing and salting
 * and gives the basic functions a proper login system needs.
 *
 * @package php-login
 * @author Panique <panique@web.de>
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// load php-login components
require_once("php-login.php");

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

// include html header and display php-login message/error
include('includes/header.php');

// the user is already logged in
if ($login->isUserLoggedIn() == true) {
	// if you need users's information, just put them into the $_SESSION variable and output them here
	echo $phplogin_lang['You are logged in as'] . $_SESSION['user_name'] . " (". $_SESSION['user_email'] .")<br />\n";
	//echo $login->user_gravatar_image_url;
	echo $phplogin_lang['Profile picture'] ."<br/>". $login->user_gravatar_image_tag . "<br />\n";
?>
	<a href="index.php?logout"><?php echo $phplogin_lang['Logout']; ?></a>
	<a href="edit.php"><?php echo $phplogin_lang['Edit user data']; ?></a>
<?php

// the user is not logged in, we show the login form
} else { ?>
	<form method="post" action="index.php" name="loginform">
		<label for="user_name"><?php echo $phplogin_lang['Username']; ?></label>
		<input id="user_name" type="text" name="user_name" required />
		<label for="user_password"><?php echo $phplogin_lang['Password']; ?></label>
		<input id="user_password" type="password" name="user_password" autocomplete="off" required />
		<input id="user_rememberme" type="checkbox" name="user_rememberme" value="1" />
		<label for="user_rememberme"><?php echo $phplogin_lang['Remember me']; ?></label>
		<input type="submit" name="login" value="<?php echo $phplogin_lang['Log in']; ?>" />
	</form>

	<a href="register.php"><?php echo $phplogin_lang['Register new account']; ?></a>
	<a href="password_reset.php"><?php echo $phplogin_lang['I forgot my password']; ?></a>	
<?php
}

// include html footer
include('includes/footer.php');
