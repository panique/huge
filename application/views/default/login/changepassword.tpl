<div class="content">
    <h1>{$lang.newpass}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <!-- new password form box -->
    <form method="post" action="{$site_path}login/setnewpassword" name="new_password_form">
        <input type='hidden' name='user_name' value='{$userName}' />
        <input type='hidden' name='user_password_reset_hash' value='{$userPasswordResetHash}' />
        <label for="reset_input_password_new">
            {$lang.passmin}
            <span class="login-form-password-pattern-reminder">
                {$lang.passsecure}
                <a href="http://security.stackexchange.com/questions/6095/xkcd-936-short-complex-password-or-long-dictionary-passphrase">
                    {$lang.passsecurelink}
                </a>.
            </span>
        </label>
        <input id="reset_input_password_new" class="reset_input" type="password"
               name="user_password_new" pattern="{literal}.{6,}{/literal}" required autocomplete="off" />
        <label for="reset_input_password_repeat">{$lang.newpassrepeat}</label>
        <input id="reset_input_password_repeat" class="reset_input" type="password"
               name="user_password_repeat" pattern="{literal}.{6,}{/literal}" required autocomplete="off" />
        <input type="submit"  name="submit_new_password" value="{$lang.newpasssubmit}" />
    </form>

    <a href="./login/index">{$lang.back}</a>
</div>
