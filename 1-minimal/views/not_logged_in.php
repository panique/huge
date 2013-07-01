<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->

<!-- errors & messages --->

<?php
/**
// show negative messages
$errors = $login->getErrors();
if (count($errors)) {
    foreach ($errors as $key => $value) {
        echo $key .':' . $error;    
    }
}
*/
?>             

<!-- login form box -->
<form method="post" action="index.php" name="loginform">
    <label for="login_input_username">Username</label>
    <input id="login_input_username" class="login_input" type="text" name="user_name" required pattern="<?php echo Auth::$regexp['user_name']; ?>">
    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required pattern="<?php echo Auth::$regexp['user_password']; ?>">
    <input type="submit" name="login" value="Log in">
</form>

<a href="register.php">Register new account</a>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->
