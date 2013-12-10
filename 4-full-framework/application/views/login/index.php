<div class="content">

    <?php
    if (isset($this->feedback["success"])) {
        foreach ($this->feedback["success"] as $feedback) {
            echo '<div class="feedback success">'.$feedback.'</div>';
        }
    } elseif (isset($this->feedback["error"])) {
        foreach ($this->feedback["error"] as $feedback) {
            echo '<div class="feedback error">'.$feedback.'</div>';
        }
    }
    ?>

    <div class="login-default-box">
        <h1>Login</h1>
        <form action="<?php echo URL; ?>login/login" method="post">
                <label>Username (or email)</label>
                <input type="text" name="user_name" required />
                <label>Password</label>
                <input type="password" name="user_password" required />
                <input type="checkbox" name="user_rememberme" class="remember-me-checkbox" />
                <label class="remember-me-label">Keep me logged in (for 2 weeks)</label>
                <input type="submit" class="login-submit-button" />
        </form>
        <a href="<?php echo URL; ?>login/register">Register</a>
        |
        <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
    </div>

    <?php if (FACEBOOK_LOGIN == true) { ?>
    <div class="login-facebook-box">
        <h1>or</h1>
        <a href="<?php echo $this->facebook_login_url; ?>" class="facebook-login-button">Log in with Facebook</a>
    </div>
    <?php } ?>

</div>
