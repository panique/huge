<?php
// include html header and display php-login message/error
include('header.php');

// login form box
?>            
<form method="post" action="index.php" name="loginform">
	<label for="user_name"><?php echo $phplogin_lang['username']; ?></label>
	<input id="user_name" type="text" name="user_name" required />
	<label for="user_password"><?php echo $phplogin_lang['password']; ?></label>
	<input id="user_password" type="password" name="user_password" autocomplete="off" required />
	<input type="checkbox" id="user_rememberme" name="user_rememberme" value="1" />
	<label for="user_rememberme"><?php echo $phplogin_lang['remember_me']; ?></label>
	<input type="submit" name="login" value="<?php echo $phplogin_lang['log_in']; ?>" />
</form>

<a href="register.php"><?php echo $phplogin_lang['register_new_account']; ?></a>
<a href="password_reset.php"><?php echo $phplogin_lang['i_forgot_my_password']; ?></a>

<?php
// include html footer
include('footer.php');
