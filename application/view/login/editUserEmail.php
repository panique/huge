<div class="content">
    <h1>Change your email address</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form action="<?php echo Config::get('URL'); ?>login/editUserEmail_action" method="post">
        <label>
            New email address: <input type="text" name="user_email" required />
        </label>
        <input type="submit" value="Submit" />
    </form>
</div>
