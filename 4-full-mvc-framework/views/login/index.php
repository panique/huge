<div class="content">

    <h1>Login</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }

    }

    ?>
    
    <form action="<?php echo URL; ?>login/login" method="post">

            <label>Username</label>
            <input type="text" name="user_name" />
            
            <label>Password</label>
            <input type="password" name="user_password" />            
            
            <input type="checkbox" name="user_rememberme" style="float: left; min-width: 0; margin: 3px 10px 15px 0;" />
            <label style="float:left; min-width: 0; font-size: 12px; color: #888;">Keep me logged in (for 2 weeks)</label>
                                    
            <input type="submit" style="float: none; clear: both;" />            
            
    </form>    
    
    <a href="<?php echo URL; ?>login/register">Register</a>
    |
    <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
    
</div>