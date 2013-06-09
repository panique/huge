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

<!-- request password reset form box -->
<form method="post" action="password_reset.php" name="password_reset_form">
    <label for="password_reset_input_username">Request a password reset. Enter your username and you'll get a mail with instructions:</label>
    <input id="password_reset_input_username" class="password_reset_input" type="text" name="user_name" required />
    <input type="submit"  name="request_password_reset" value="Reset my password" />
</form>

<a href="index.php">Back to Login Page</a>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->