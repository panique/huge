<?php
// include html header and display php-login message/error
include('header.php');

// show negative messages
if ($registration->errors) {
    foreach ($registration->errors as $error) {
        echo $error;
    }
}

// show positive messages
if ($registration->messages) {
    foreach ($registration->messages as $message) {
        echo $message;
    }
}

// show register form
// - the user name input field uses a HTML5 pattern check
// - the email input field uses a HTML5 email type check
if (!$registration->registration_successful && !$registration->verification_successful) { ?>

<form method="post" action="register.php" name="registerform">   
	<label for="user_name"><?php echo $phplogin_lang['Register username']; ?></label>
	<input id="user_name" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />

	<label for="user_email"><?php echo $phplogin_lang['Register email']; ?></label>
	<input id="user_email" type="email" name="user_email" required />

	<label for="user_password_new"><?php echo $phplogin_lang['Register password']; ?></label>
	<input id="user_password_new" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />  

	<label for="user_password_repeat"><?php echo $phplogin_lang['Register password repeat']; ?></label>
	<input id="user_password_repeat" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />        

	<img src="tools/showCaptcha.php" alt="captcha" />

	<label><?php echo $phplogin_lang['Register captcha']; ?></label>
	<input type="text" name="captcha" required />

	<input type="submit" name="register" value="<?php echo $phplogin_lang['Register']; ?>" />
</form>
<?php } ?>

<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>
<?php
// include html footer
include('footer.php');
