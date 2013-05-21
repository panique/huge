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

<h2>
    Hey, <?php echo $_SESSION['user_name']; ?>. You are logged in and can edit your credentials here:
</h2>

<!-- edit form for username / this form uses HTML5 attributes, like "required" and type="email" -->
<form method="post" action="edit.php" name="user_edit_form_name">
    <label for="edit_input_username">New username (username cannot be empty and must be azAZ09 and 2-64 characters)</label><br/>
    <input id="edit_input_username" class="login_input" type="text" name="user_name" required /> (currently: <?php echo $_SESSION['user_name']; ?>)<br/>
    <input type="submit"  name="user_edit_submit_name" value="Change username" />
</form>

<!-- edit form for user email / this form uses HTML5 attributes, like "required" and type="email" -->
<form method="post" action="edit.php" name="user_edit_form_email">
    <label for="edit_input_email">New email</label><br/>
    <input id="edit_input_email" class="login_input" type="email" name="user_email" required /> (currently: <?php echo $_SESSION['user_email']; ?>)<br/>
    <input type="submit"  name="user_edit_submit_email" value="Change email" />
</form>

    <!--
    <label for="edit_input_email">New email</label>
    <input id="edit_input_email" class="login_input" type="text" name="user_email" /> (currently: <?php echo $_SESSION['user_email'] ?: "none"; ?>)        
    <br/>
    <label for="edit_input_password_new">Your NEW Password (leave blank if you don't want to change)</label>
    <input id="edit_input_password_new" class="login_input" type="password" name="user_password_new" autocomplete="off" />        
    <br/>
    <label for="edit_input_password_repeat">Repeat NEW password</label>
    <input id="edit_input_password_repeat" class="login_input" type="password" name="user_password_repeat" autocomplete="off" />        
    <br/>
    -->


<!-- backlink -->
<a href="index.php">Back to Index Page</a>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->