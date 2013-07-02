<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->

<!-- errors & messages -->

<?php
$errors = array();
if (! $login->isUserLoggedIn()) {
    // show negative messages
    $errors = $login->getErrors();
}
?>             
<!-- login form box -->
<form method="post" action="index.php?action=login" name="loginform">
    <div>
    <label for="login_input_username">Username</label>
    <input id="login_input_username" class="login_input" type="text" name="user_name" required pattern="<?php echo Auth::$regexp['user_name']; ?>">
    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required pattern="<?php echo Auth::$regexp['user_password']; ?>">
    <input type="submit" value="Log in">
    </div>
<?php if (count($errors)) : ?>
    <?php if (isset($errors['user_name'])): ?>
        <?php if (Auth::DATA_MISSING == $errors['user_name']) : ?>
            <p><strong>You did not submit a login</strong></p>
        <?php elseif (Auth::DATA_INVALID == $errors['user_name']) : ?>
            <p><strong>The submitted login is invalid!!</strong></p>
         <?php elseif (Auth::USER_UNKNOWN == $errors['user_name']) : ?>
            <p><strong>unknown account!</strong></p>
       <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($errors['user_password'])): ?>
        <?php if (Auth::DATA_MISSING == $errors['user_password']) : ?>
            <p><strong>You did not submit an password</strong></p>
        <?php elseif (Auth::DATA_INVALID == $errors['user_password']) : ?>
            <p><strong>The submitted password is invalid!!</strong></p>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
</form>

<a href="register.php">Register new account</a>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->
