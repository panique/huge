<?php
// include html header and display php-login message/error
include('header.php');

// login form box
?>            
<form method="post" action="index.php" name="loginform">
	<label for="user_name"><?php echo $phplogin_lang['Username']; ?></label>
	<input id="user_name" type="text" name="user_name" required />
	<label for="user_password"><?php echo $phplogin_lang['Password']; ?></label>
	<input id="user_password" type="password" name="user_password" autocomplete="off" required />
	<input type="checkbox" id="user_rememberme" name="user_rememberme" value="1" />
	<label for="user_rememberme"><?php echo $phplogin_lang['Remember me']; ?></label>
	<input type="submit" name="login" value="<?php echo $phplogin_lang['Log in']; ?>" />
</form>

<a href="register.php"><?php echo $phplogin_lang['Register new account']; ?></a>
<a href="password_reset.php"><?php echo $phplogin_lang['I forgot my password']; ?></a>

<?php
// include html footer
include('footer.php');
