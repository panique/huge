<div class="content">

    <h1>Login</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }

    }

    ?>

    <!-- request password reset form box -->
    <form method="post" action="requestpasswordreset_action" name="password_reset_form">
        <label for="password_reset_input_username">
            Request a password reset.<br/>
            Enter your username and you'll get a mail with instructions:
        </label>
        <input id="password_reset_input_username" class="password_reset_input" type="text" name="user_name" required />
        <input type="submit"  name="request_password_reset" value="Reset my password" />
    </form>

    <a href="index">Back to Login Page</a>
    
</div>