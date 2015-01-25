<div class="container">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="login-page-box">
        <div class="table-wrapper">

            <!-- login box on left side -->
            <div class="login-box">
                <h2>Login here</h2>
                <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
                    <input type="text" name="user_name" placeholder="Username or email" required />
                    <input type="password" name="user_password" placeholder="Password" required />
                    <label for="set_remember_me_cookie" class="remember-me-label">
                        <input type="checkbox" name="set_remember_me_cookie" class="remember-me-checkbox" />
                        Remember me for 2 weeks
                    </label>
                    <input type="submit" class="login-submit-button" value="Log in"/>
                </form>
                <div class="link-forgot-my-password">
                    <a href="<?php echo Config::get('URL'); ?>login/requestPasswordReset">I forgot my password</a>
                </div>
            </div>

            <!-- register box on right side -->
            <div class="register-box">
                <h2>No account yet ?</h2>
                <a href="<?php echo Config::get('URL'); ?>login/register">Register</a>
            </div>

        </div>
    </div>
</div>
