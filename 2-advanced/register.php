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

// create the login object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$login = new Registration();

// include html header and display php-login message/error
include('includes/header.php');

// showing the register view (with the registration form, and messages/errors)
// show register form
// - the user name input field uses a HTML5 pattern check
// - the email input field uses a HTML5 email type check
if (!$login->registration_successful && !$login->verification_successful) { ?>

<form method="post" action="register.php" name="registerform">   
    <label for="user_name"><?php echo $phplogin_lang['Register username']; ?></label>
    <input id="user_name" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />

    <label for="user_email"><?php echo $phplogin_lang['Register email']; ?></label>
    <input id="user_email" type="email" name="user_email" required />

    <label for="user_password_new"><?php echo $phplogin_lang['Register password']; ?></label>
    <input id="user_password_new" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />

    <label for="user_password_repeat"><?php echo $phplogin_lang['Register password repeat']; ?></label>
    <input id="user_password_repeat" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />       

    <img src="register_captcha.php" alt="captcha" style="display: block;"/>

    <label><?php echo $phplogin_lang['Register captcha']; ?></label>
    <input type="text" name="captcha" required />

    <input type="submit" name="register" value="<?php echo $phplogin_lang['Register']; ?>" />
</form>

<?php } ?>

<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>

<?php
// include html footer
include('includes/footer.php');
