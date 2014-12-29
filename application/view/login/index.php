<div class="container">
    <h1>LoginController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>
        <p>
            xxxxx
        </p>
        <div>
            <h1>Login</h1>
            <form action="<?php echo URL; ?>login/login" method="post">
                <input type="text" name="user_name" placeholder="Username (or email)" required />
                <input type="password" name="user_password" placeholder="Password" required />
                <input type="checkbox" name="user_rememberme" class="remember-me-checkbox" />
                <label class="remember-me-label">Remember me for 2 weeks</label>
                <input type="submit" class="login-submit-button" value="Log in"/>
            </form>
            <div>
                <a href="<?php echo URL; ?>login/register">Register</a>
                |
                <a href="<?php echo URL; ?>login/requestPasswordReset">Forgot my Password</a>
            </div>
        </div>
    </div>
</div>
