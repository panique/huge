<div class="content">
    <h1>Request a password reset</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <!-- request password reset form box -->
    <form method="post" action="<?php echo URL; ?>login/requestpasswordreset_action" name="password_reset_form">
        <label for="password_reset_input_username">
            Enter your username and you'll get a mail with instructions:
        </label>
        <input id="password_reset_input_username" class="password_reset_input" type="text" name="user_name" required />
        <input type="submit"  name="request_password_reset" value="Reset my password" />
    </form>
</div>
