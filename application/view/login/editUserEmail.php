<div class="content">
    <div class="page-header text-center">
        <h1>LoginController/editUserEmail<small></small></h1>
    </div>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <div class="well">
        <h3>Change your email address</h3>
        <form action="<?php echo Config::get('URL'); ?>login/editUserEmail_action" method="post">
            <label>
                New email address: <input type="text" name="user_email" required />
            </label>
            <input type="submit" value="Submit" />
        </form>
    </div>
</div>
