<div class="content">
    <h1><?php echo Lang::__("login.editusername.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form action="<?php echo URL; ?>login/editusername_action" method="post">
        <label><?php echo Lang::__("login.editusername.label.username");?></label>
        <input type="text" name="user_name" required />
        <input type="submit" value="<?php echo Lang::__("login.editusername.submit");?>" />
    </form>
</div>
