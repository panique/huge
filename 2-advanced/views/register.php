<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->

<!-- errors & messages --->
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

?>   

<?php if (!$registration->registration_successful && !$registration->verification_successful) { ?>

<!-- register form -->
<form method="post" action="register.php" name="registerform">   
    
    <!-- NOTE: those <br/> are bad style and only there for basic formatting. remove them when you use real .css -->
    
    <!-- the user name input field uses a HTML5 pattern check -->
    <label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label><br/>
    <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required /><br/><br/>
    
    <!-- the email input field uses a HTML5 email type check -->
    <label for="login_input_email">User's email (please provide a real email adress, you'll get a verification mail with an activation link)</label><br/>
    <input id="login_input_email" class="login_input" type="email" name="user_email" required /><br/><br/>
    
    <label for="login_input_password_new">
        Password (min. 6 characters!<br/>
        Please note: using a long sentence as a password is much much safer then something like "!c00lPa$$w0rd").<br/> 
        Have a look on <a href="http://security.stackexchange.com/questions/6095/xkcd-936-short-complex-password-or-long-dictionary-passphrase">this interesting security.stackoverflow.com thread</a>.
    </label><br/>
    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" /><br/><br/>  
    
    <label for="login_input_password_repeat">Repeat password</label><br/>
    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" /><br/><br/>        
    
    <!-- generate and display a captcha and write the captcha string into session -->
    <img src="tools/showCaptcha.php" /><br/>
    
    <label>Please enter those characters</label><br/>
    <input type="text" name="captcha" /><br/><br/>      
    
    <input type="submit"  name="register" value="Register" /><br/><br/>
    
</form>

<?php } ?>

<!-- backlink -->
<a href="index.php">Back to Login Page</a>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->