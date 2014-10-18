<div class="content">
    <h1><?php echo Lang::__("login.requestpasswordreset.title")?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <!-- request password reset form box -->
    <form method="post" action="<?php echo URL; ?>login/requestpasswordreset_action" name="password_reset_form">
        <label for="password_reset_input_username">
            <?php echo Lang::__("login.requestpasswordreset.label.username")?>
        </label>
        <input id="password_reset_input_username" class="password_reset_input" type="text" name="user_name" required />
        <input type="submit"  name="request_password_reset" value="<?php echo Lang::__("login.requestpasswordreset.submit")?>" />
    </form>
</div>
