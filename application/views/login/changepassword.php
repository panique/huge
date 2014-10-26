<div class="content">
    <h1><?php echo Lang::__("login.changepassword.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <!-- new password form box -->
    <form method="post" action="<?php echo URL; ?>login/setnewpassword" name="new_password_form">
        <input type='hidden' name='user_name' value='<?php echo $this->user_name; ?>' />
        <input type='hidden' name='user_password_reset_hash' value='<?php echo $this->user_password_reset_hash; ?>' />
        <label for="reset_input_password_new">
            <?php echo Lang::__("login.changepassword.label.newpassword");?>
            <span class="login-form-password-pattern-reminder">
			<?php echo Lang::__("login.changepassword.reminder.password");?>    
            </span>
        </label>
        <input id="reset_input_password_new" class="reset_input" type="password"
               name="user_password_new" pattern=".{6,}" required autocomplete="off" />
        <label for="reset_input_password_repeat"><?php echo Lang::__("login.changepassword.label.repeatnewpassword");?></label>
        <input id="reset_input_password_repeat" class="reset_input" type="password"
               name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
        <input type="submit"  name="submit_new_password" value="<?php echo Lang::__("login.changepassword.submit");?>" />
    </form>

    <a href="<?php echo URL; ?>login/index"><?php echo Lang::__("login.backlink");?></a>
</div>
