<h1>Member Login</h1>

<?php

if ($login->errors) {
    foreach ($login->errors as $error) {
        echo $error."<br />";
    }
}

?>

<h3>Please provide username and password</h3>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="loginform" id="loginform">
    <fieldset>
        <label for="user_name">Username:</label><input type="text" name="user_name" id="user_name" /><br />
        <label for="user_password">Password:</label><input type="password" name="user_password" id="user_password" /><br />
        <input type="submit" name="login" id="login" value="Login" />
    </fieldset>
</form>


<a href="index.php?action=register">
    Register new user
</a>