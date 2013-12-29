<div class="content">
    <h1>Change your username</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form action="<?php echo URL; ?>login/editusername_action" method="post">
        <label>New username</label>
        <input type="text" name="user_name" required />
        <input type="submit" value="Submit" />
    </form>
</div>
