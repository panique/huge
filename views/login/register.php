
<?php

if ($login->errors) {
    foreach ($login->errors as $error) {
        echo $error."<br />";
    }
}

if ($login->messages) {
    foreach ($login->messages as $message) {
        echo $message."<br />";
    }
}

?>

   <h1>Register</h1>
   <a href="index.php">Back to login</a>
    
   <p>Please enter your details below to register.</p>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=register" name="registerform" id="registerform">
    <fieldset>
            <label for="user_name">Username:</label><input type="text" name="user_name" id="user_name" /><br />
            <label for="user_password">Password:</label><input type="user_password" name="user_password" id="user_password" /><br />
    <label for="user_email">Email Address:</label><input type="text" name="user_email" id="user_email" /><br />
            <input type="submit" name="register" id="register" value="Register" />
    </fieldset>
    </form>