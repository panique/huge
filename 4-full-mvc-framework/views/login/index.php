<div class="content">

    <h1>Login</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }

    }

    ?>

    <form action="login/login" method="post">

            <label>Login</label><input type="text" name="user_name" /><br />
            <label>Password</label><input type="password" name="user_password" /><br />
            <label></label><input type="submit" />
    </form>
    
    <a href="<?php echo URL; ?>login/register">Register</a>
    |
    <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
    
</div>