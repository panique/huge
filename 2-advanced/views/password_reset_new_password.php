<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->

<!-- errors & messages --->

<?php

// show negative messages
if ($login->errors) {
    foreach ($login->errors as $error) {
        echo $error;    
    }
}

// show positive messages
if ($login->messages) {
    foreach ($login->messages as $message) {
        echo $message;
    }
}

?>             

<!-- new password form box -->
<form method="post" action="password_reset.php" name="new_password_form">
    
    <input type='hidden' name='user_name' value='<?php echo $login->getUsername(); ?>' />
    <input type='hidden' name='user_password_reset_hash' value='<?php echo $login->getPasswordResetHash(); ?>' />
    
    <label for="reset_input_password_new">New password (min. 6 characters)</label>
    <input id="reset_input_password_new" class="reset_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />  
    
    <label for="reset_input_password_repeat">Repeat new password</label>
    <input id="reset_input_password_repeat" class="reset_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />        
    <input type="submit"  name="submit_new_password" value="Submit new password" />
</form>

<a href="index.php">Back to Login Page</a>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->