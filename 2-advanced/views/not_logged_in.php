<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP-login</title>
</head>
<body>
<?php

// show negative messages
foreach ($login->errors as $error) {
	echo $error;    
}

// show positive messages
foreach ($login->messages as $message) {
	echo $message;
}

// login form box
?>            
<form method="post" action="index.php" name="loginform">
	<label for="login_input_username"><?php echo $phplogin_lang['Username']; ?></label><br/>
	<input id="login_input_username" class="login_input" type="text" name="user_name" required /><br/><br/>
	<label for="login_input_password"><?php echo $phplogin_lang['Password']; ?></label><br/>
	<input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required /><br/><br/>
	<input type="checkbox" id="login_input_rememberme" name="user_rememberme" value="1" /> <?php echo $phplogin_lang['Remember me']; ?><br/><br/>
	<input type="submit"  name="login" value="<?php echo $phplogin_lang['Log in']; ?>" /><br/><br/>
</form>

<a href="register.php"><?php echo $phplogin_lang['Register new account']; ?></a>
<a href="password_reset.php"><?php echo $phplogin_lang['I forgot my password']; ?></a>
</body>
</html>
