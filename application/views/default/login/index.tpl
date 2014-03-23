<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <div class="login-default-box">
        <h1>Login</h1>
        <form action="./login/login" method="post">
                <label>Username (or email)</label>
                <input type="text" name="user_name" required />
                <label>Password</label>
                <input type="password" name="user_password" required />
                <input type="checkbox" name="user_rememberme" class="remember-me-checkbox" />
                <label class="remember-me-label">Keep me logged in (for 2 weeks)</label>
                <input type="submit" class="login-submit-button" />
        </form>
        <a href="./login/register">Register</a>
        |
        <a href="./login/requestpasswordreset">Forgot my Password</a>
    </div>

    {if $fblogin}
    <div class="login-facebook-box">
        <h1>or</h1>
        <a href="{$fbloginurl}" class="facebook-login-button">Log in with Facebook</a>
    </div>
    {/if}

</div>
