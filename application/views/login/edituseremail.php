<div class="content">
    <h1><?php echo Lang::__("login.edituseremail.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form action="<?php echo URL; ?>login/edituseremail_action" method="post">
        <label><?php echo Lang::__("login.edituseremail.label.useremail");?></label>
        <input type="text" name="user_email" required />
        <input type="submit" value="<?php echo Lang::__("login.edituseremail.submit");?>" />
    </form>
</div>
