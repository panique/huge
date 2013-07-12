<div class="content">

    <h1>Login</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }

    }

    ?>
    
    <form action="<?php echo URL."/login/login"; ?>" method="post">

            <label>Username</label>
            <input type="text" name="user_name" />
            
            <label>Password</label>
            <input type="password" name="user_password" />
            
            <label></label><input type="submit" />            
            
    </form>    
    
    <a href="<?php echo URL; ?>login/register">Register</a>
    |
    <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
    
</div>