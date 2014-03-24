<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <div class="register-default-box">
        <h1>{$lang.register}</h1>
        <!-- register form -->
        <form method="post" action="{$site_path}register_action" name="registerform">
            <!-- the user name input field uses a HTML5 pattern check -->
            <label for="login_input_username">
                {$lang.regisuser}
                <span style="display: block; font-size: 14px; color: #999;">{$lang.regisuserprop}</span>
            </label>
            <input id="login_input_username" class="login_input" type="text" pattern="{literal}[a-zA-Z0-9]{2,64}{/literal}" name="user_name" required />
            <!-- the email input field uses a HTML5 email type check -->
            <label for="login_input_email">
                {$lang.regisemail}
                <span style="display: block; font-size: 14px; color: #999;">
                    {$lang.regisemail1} <span style="text-decoration: underline; color: mediumvioletred;">{$lang.regisemail2}</span>,
                    {$lang.regisemail3}
                </span>
            </label>
            <input id="login_input_email" class="login_input" type="email" name="user_email" required />
            <label for="login_input_password_new">
                {$lang.passmin}
                <span class="login-form-password-pattern-reminder">
                    {$lang.passsecure}
                    <a href="http://security.stackexchange.com/questions/6095/xkcd-936-short-complex-password-or-long-dictionary-passphrase">
                        {$lang.passsecurelink}
                    </a>.
                </span>
            </label>
            <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern="{literal}.{6,}{/literal}" required autocomplete="off" />
            <label for="login_input_password_repeat">{$lang.regisrepeatpass}</label>
            <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern="{literal}.{6,}{/literal}" required autocomplete="off" />
            <!-- show the captcha by calling the login/showCaptcha-method in the src attribute of the img tag -->
            <!-- to avoid weird with-slash-without-slash issues: simply always use the URL constant here -->
            <img src="{$site_path}showCaptcha" alt="..." />
            <label>
                {$lang.regiscaptcha1}
                <span style="display: block; font-size: 11px; color: #999;">
                    {$lang.regiscaptcha2}
                </span>
            </label>
            <input type="text" name="captcha" required />
            <input type="submit"  name="register" value="{$lang.register}" />

        </form>
    </div>

    {if $fblogin}
        <div class="register-facebook-box">
            <h1>{$lang.orheader}</h1>
            <a href="{$fbloginurl}" class="facebook-login-button">{$lang.regisfb}</a>
        </div>
    {/if}

</div>
