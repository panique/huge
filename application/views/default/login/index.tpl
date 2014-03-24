<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <div class="login-default-box">
        <h1>{$lang.header}</h1>
        <form action="{$site_path}login/login" method="post">
                <label>{$lang.indexlogin}</label>
                <input type="text" name="user_name" required />
                <label>{$lang.indexpassword}</label>
                <input type="password" name="user_password" required />
                <input type="checkbox" name="user_rememberme" class="remember-me-checkbox" />
                <label class="remember-me-label">{$lang.indexstaylogged}</label>
                <input type="submit" class="login-submit-button" />
        </form>
        <a href="{$site_path}login/register">{$lang.register}</a>
        |
        <a href="{$site_path}login/requestpasswordreset">{$lang.indexforgot}</a>
    </div>

    {if $fblogin}
    <div class="login-facebook-box">
        <h1>{$lang.orheader}</h1>
        <a href="{$fbloginurl}" class="facebook-login-button">{$lang.indexfblogin}</a>
    </div>
    {/if}

</div>
