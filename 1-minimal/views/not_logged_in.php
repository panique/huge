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

<!-- login form box -->
<form method="post" action="index.php" name="loginform">
            <input id="login_input_username" class="login_input" type="text" name="user_name" value="<?php echo $login->view_user_name; ?>" />
            <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" />
            <input type="submit"  name="login" value="Submit" />            
</form>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->