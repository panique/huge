<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP-login</title>
</head>
<body>
<?php

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
// - NOTE: those <br/> are bad style and only there for basic formatting. remove them when you use real .css
// - the user name input field uses a HTML5 pattern check
// - the email input field uses a HTML5 email type check
if (!$registration->registration_successful && !$registration->verification_successful) { ?>

<form method="post" action="register.php" name="registerform">   
    <label for="login_input_username"><?php echo $phplogin_lang['Register username']; ?></label><br/>
    <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required /><br/><br/>

    <label for="login_input_email"><?php echo $phplogin_lang['Register email']; ?></label><br/>
    <input id="login_input_email" class="login_input" type="email" name="user_email" required /><br/><br/>

    <label for="login_input_password_new"><?php echo $phplogin_lang['Register password']; ?></label><br/>
    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" /><br/><br/>  

    <label for="login_input_password_repeat"><?php echo $phplogin_lang['Register password repeat']; ?></label><br/>
    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" /><br/><br/>        

    <img src="tools/showCaptcha.php" alt="captcha" /><br/>

    <label><?php echo $phplogin_lang['Register captcha']; ?></label><br/>
    <input type="text" name="captcha" required /><br/><br/>

    <input type="submit" name="register" value="<?php echo $phplogin_lang['Register']; ?>" /><br/><br/>
</form>

<?php } ?>

<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>
</body>
</html>
