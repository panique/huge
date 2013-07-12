<div class="content">

    <h1>Register</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }

    }

    ?>

    <!-- register form -->
    <form method="post" action="<?php echo URL."/login/register_action"; ?>" name="registerform">

        <!-- the user name input field uses a HTML5 pattern check -->
        <label for="login_input_username">
            Username
            <span style="display: block; font-size: 14px; color: #999;">(only letters and numbers, 2 to 64 characters)</span>
        </label>
        <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />

        <!-- the email input field uses a HTML5 email type check -->
        <label for="login_input_email">User's email</label>    
        <input id="login_input_email" class="login_input" type="email" name="user_email" required />

        <label for="login_input_password_new">Password</label>
        <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />  

        <label for="login_input_password_repeat">Repeat password</label>
        <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
        
        <!-- show the captcha by calling the login/showCaptcha-method in the src attribute of the img tag -->
        <!-- to avoid weird with-slash-without-slash issues: simply always use the URL constant here -->
        <img src="<?php echo URL."/login/showCaptcha"; ?>" />

        <label>Please enter those characters</label>
        <input type="text" name="captcha" />           
        
        <input type="submit"  name="register" value="Register" />
        
    </form>
    
</div>