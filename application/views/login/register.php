<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="register-default-box">
        <h1><?php echo Lang::__("login.register.title");?></h1>
        <!-- register form -->
        <form method="post" action="<?php echo URL; ?>login/register_action" name="registerform">
            <!-- the user name input field uses a HTML5 pattern check -->
            <label for="login_input_username">
                <?php echo Lang::__("login.register.label.username");?>
                <span style="display: block; font-size: 14px; color: #999;"><?php echo Lang::__("login.register.reminder.username");?></span>
            </label>
            <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
            <!-- the email input field uses a HTML5 email type check -->
            <label for="login_input_email">
                <?php echo Lang::__("login.register.label.useremail");?>
                <span style="display: block; font-size: 14px; color: #999;">
                    <?php echo Lang::__("login.register.reminder.useremail");?>
                </span>
            </label>
            <input id="login_input_email" class="login_input" type="email" name="user_email" required />
            <label for="login_input_password_new">
                <?php echo Lang::__("login.register.label.password");?>
                <span class="login-form-password-pattern-reminder">
				<?php echo Lang::__("login.register.reminder.password");?>
                </span>
            </label>
            <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
            <label for="login_input_password_repeat"><?php echo Lang::__("login.register.label.repeatpassword");?></label>
            <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
            <!-- show the captcha by calling the login/showCaptcha-method in the src attribute of the img tag -->
            <!-- to avoid weird with-slash-without-slash issues: simply always use the URL constant here -->
            <img id="captcha" src="<?php echo URL; ?>login/showCaptcha" />
            <span style="display: block; font-size: 11px; color: #999; margin-bottom: 10px">
                <!-- quick & dirty captcha reloader -->
                <a href="#" onclick="document.getElementById('captcha').src = '<?php echo URL; ?>login/showCaptcha?' + Math.random(); return false">[ Reload Captcha ]</a>
            </span>
            <label>
                <?php echo Lang::__("login.register.label.captcha");?>
                <span style="display: block; font-size: 11px; color: #999;">
				<?php echo Lang::__("login.register.reminder.captcha");?>
                </span>
            </label>
            <input type="text" name="captcha" required />
            <input type="submit"  name="register" value="<?php echo Lang::__("login.register.submit");?>" />

        </form>
    </div>

    <?php if (FACEBOOK_LOGIN == true) { ?>
        <div class="register-facebook-box">
            <h1>or</h1>
            <a href="<?php echo $this->facebook_register_url; ?>" class="facebook-login-button"><?php echo Lang::__("registerwithfacebook");?></a>
        </div>
    <?php } ?>

</div>
