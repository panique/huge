<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="login-default-box">
        <h1><?php echo Lang::__("login.index.title")?></h1>
        <form action="<?php echo URL; ?>login/login" method="post">
                <label><?php echo Lang::__("login.index.label.username");?></label>
                <input type="text" name="user_name" required />
                <label><?php echo Lang::__("login.index.label.password");?></label>
                <input type="password" name="user_password" required />
                <input type="checkbox" name="user_rememberme" class="remember-me-checkbox" />
                <label class="remember-me-label"><?php echo Lang::__("login.index.label.rememberme");?></label>
                <input type="submit" class="login-submit-button" value='<?php echo Lang::__("login.index.submit");?>'/>
        </form>
        <a href="<?php echo URL; ?>login/register"><?php echo Lang::__("link.user.register");?></a>
        |
        <a href="<?php echo URL; ?>login/requestpasswordreset"><?php echo Lang::__("link.user.forgottenpassword");?></a>
    </div>

    <?php if (FACEBOOK_LOGIN == true) { ?>
    <div class="login-facebook-box">
        <h1>or</h1>
        <a href="<?php echo $this->facebook_login_url; ?>" class="facebook-login-button"><?php echo Lang::__("link.user.loginwithfacebook");?></a>
    </div>
    <?php } ?>

</div>
