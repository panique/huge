<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->
<?php 
if (! $registration->isRegistrationSuccessful()) {
    // show negative messages
    $errors = $registration->getErrors();
    ?>
    <!-- register form -->
    <form method="post" action="register.php?action=register" name="registerform">   
    <!-- the user name input field uses a HTML5 pattern check -->
    <div>
    <label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>
    <input id="login_input_username" class="login_input" type="text" placeholder="login" pattern="<?php echo Auth::$regexp['user_name']; ?>" name="user_name" required>
<?php if (isset($errors['user_name'])): ?>
    <?php if (Auth::DATA_MISSING == $errors['user_name']) : ?>
        <p><strong>You did not submit a login</strong></p>
    <?php elseif (Auth::DATA_INVALID == $errors['user_name']) : ?>
        <p><strong>The submitted login is invalid!!</strong></p>
    <?php elseif (Auth::USER_EXISTS == $errors['user_name']) : ?>
        <p><strong>an account with the same credentails already exists</strong></p>
    <?php elseif (Auth::REGISTRATION_FAILED == $errors['user_name']) : ?>
        <p><strong>An error occurs during your registration please try again later!</strong></p>
    <?php endif; ?>
<?php endif; ?>
    </div>

    <div>
    <!-- the email input field uses a HTML5 email type check -->
    <label for="login_input_email">User's email</label>    
    <input id="login_input_email" class="login_input" type="email" placeholder="my@email.com" name="user_email" required>
<?php if (isset($errors['user_email'])): ?>
    <?php if (Auth::DATA_MISSING == $errors['user_email']) : ?>
        <p><strong>You did not submit an email</strong></p>
    <?php elseif (Auth::DATA_INVALID == $errors['user_email']) : ?>
        <p><strong>The submitted email is invalid or is longer than 64 characters!!</strong></p>
    <?php endif; ?>
<?php endif; ?>
    </div>

    <div>
    <label for="login_input_password_new">Password (min. 6 characters)</label>
    <input id="login_input_password_new" class="login_input" placeholder="my password" type="password" name="user_password_new" pattern="<?php echo Auth::$regexp['user_password']; ?>" required autocomplete="off">  
<?php if (isset($errors['user_password_new'])): ?>
    <?php if (Auth::DATA_MISSING == $errors['user_password_new']) : ?>
        <p><strong>You did not submit an password</strong></p>
    <?php elseif (Auth::DATA_INVALID == $errors['user_password_new']) : ?>
        <p><strong>The submitted password is invalid or is shorter than 6 characters!!</strong></p>
    <?php endif; ?>
<?php endif; ?>
    </div>

    <div>
    <label for="login_input_password_repeat">Repeat password</label>
    <input id="login_input_password_repeat" class="login_input" placeholder="my repeated password"type="password" name="user_password_repeat" pattern="<?php echo Auth::$regexp['user_password']; ?>" required autocomplete="off">        
    <input type="submit" value="Register">
<?php if (isset($errors['user_password_repeat'])): ?>
    <?php if (Auth::DATA_MISSING == $errors['user_password_repeat']) : ?>
        <p><strong>You did not submit an password</strong></p>
    <?php elseif (Auth::DATA_INVALID == $errors['user_password_repeat']) : ?>
        <p><strong>The submitted password is invalid or is shorter than 6 characters!!</strong></p>
    <?php elseif (Auth::DATA_MISMATCH == $errors['user_password_repeat']) : ?>
        <p><strong>The submitted passwords do not match!!</strong></p>
    <?php endif; ?>
<?php endif; ?>
    </div>
    </form>
    <?php 
} else {
    ?>
    <p>You are register, to Log click on the link below.</p>
    <?php
}
?>
<!-- backlink -->
<a href="index.php">Back to Login Page</a>
<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->
