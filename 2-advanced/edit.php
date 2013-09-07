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
require_once("php-login.php");

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

// include html header and display php-login message/error
include('includes/header.php');

// the user is already logged in
if ($login->isUserLoggedIn() == true) { ?>
<h2><?php echo $_SESSION['user_name'] .' '. $phplogin_lang['Edit title']; ?></h2>

	<!-- edit form for username / this form uses HTML5 attributes, like "required" and type="email" -->
	<form method="post" action="edit.php" name="user_edit_form_name">
		<label for="user_name"><?php echo $phplogin_lang['New username']; ?></label>
		<input id="user_name" type="text" name="user_name" pattern="[a-zA-Z0-9]{2,64}" required /> (<?php echo $phplogin_lang['currently']; ?>: <?php echo $_SESSION['user_name']; ?>)
		<input type="submit"  name="user_edit_submit_name" value="<?php echo $phplogin_lang['Change username']; ?>" />
	</form><hr/>

	<!-- edit form for user email / this form uses HTML5 attributes, like "required" and type="email" -->
	<form method="post" action="edit.php" name="user_edit_form_email">
		<label for="user_email"><?php echo $phplogin_lang['New email']; ?></label>
		<input id="user_email" type="email" name="user_email" required /> (<?php echo $phplogin_lang['currently']; ?>: <?php echo $_SESSION['user_email']; ?>)
		<input type="submit"  name="user_edit_submit_email" value="<?php echo $phplogin_lang['Change email']; ?>" />
	</form><hr/>

	<!-- edit form for user's password / this form uses the HTML5 attribute "required" -->
	<form method="post" action="edit.php" name="user_edit_form_password">
		<label for="user_password_old"><?php echo $phplogin_lang['Old password']; ?></label>
		<input id="user_password_old" type="password" name="user_password_old" autocomplete="off" />        

		<label for="user_password_new"><?php echo $phplogin_lang['New password']; ?></label>
		<input id="user_password_new" type="password" name="user_password_new" autocomplete="off" />        

		<label for="user_password_repeat"><?php echo $phplogin_lang['Repeat new password']; ?></label>
		<input id="user_password_repeat" type="password" name="user_password_repeat" autocomplete="off" />        

		<input type="submit"  name="user_edit_submit_password" value="<?php echo $phplogin_lang['Change password']; ?>" />
	</form><hr/>

<?php } ?>

<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>

<?php
// include html footer
include('includes/footer.php');
