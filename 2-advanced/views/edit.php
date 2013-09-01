<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP-login</title>
</head>
<body>
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
<?php echo $_SESSION['user_name'] .' '. $phplogin_lang['Edit title']; ?>
</h2>

<!-- edit form for username / this form uses HTML5 attributes, like "required" and type="email" -->
<form method="post" action="edit.php" name="user_edit_form_name">
    <label for="edit_input_username"><?php echo $phplogin_lang['New username']; ?></label><br/>
    <input id="edit_input_username" class="login_input" type="text" name="user_name" pattern="[a-zA-Z0-9]{2,64}" required /> (<?php echo $phplogin_lang['currently']; ?>: <?php echo $_SESSION['user_name']; ?>)<br/>
    <input type="submit"  name="user_edit_submit_name" value="<?php echo $phplogin_lang['Change username']; ?>" />
</form><br/>

<!-- edit form for user email / this form uses HTML5 attributes, like "required" and type="email" -->
<form method="post" action="edit.php" name="user_edit_form_email">
    <label for="edit_input_email"><?php echo $phplogin_lang['New email']; ?></label><br/>
    <input id="edit_input_email" class="login_input" type="email" name="user_email" required /> (<?php echo $phplogin_lang['currently']; ?>: <?php echo $_SESSION['user_email']; ?>)<br/>
    <input type="submit"  name="user_edit_submit_email" value="<?php echo $phplogin_lang['Change email']; ?>" />
</form><br/>

<!-- edit form for user's password / this form uses the HTML5 attribute "required" -->
<form method="post" action="edit.php" name="user_edit_form_password">
    <label for="edit_input_password_old"><?php echo $phplogin_lang['Old password']; ?></label>
    <input id="edit_input_password_old" class="login_input" type="password" name="user_password_old" autocomplete="off" />        
    <br/>
    <label for="edit_input_password_new"><?php echo $phplogin_lang['New password']; ?></label>
    <input id="edit_input_password_new" class="login_input" type="password" name="user_password_new" autocomplete="off" />        
    <br/>
    <label for="edit_input_password_repeat"><?php echo $phplogin_lang['Repeat new password']; ?></label>
    <input id="edit_input_password_repeat" class="login_input" type="password" name="user_password_repeat" autocomplete="off" />        
    <br/>
    <input type="submit"  name="user_edit_submit_password" value="<?php echo $phplogin_lang['Change password']; ?>" />
</form><br/>

<!-- backlink -->
<a href="index.php"><?php echo $phplogin_lang['Back to login']; ?></a>
</body>
</html>
