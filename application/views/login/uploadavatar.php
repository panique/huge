<div class="content">
    <h1><?php echo Lang::__("login.uploadavatar.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form action="<?php echo URL; ?>login/uploadavatar_action" method="post" enctype="multipart/form-data">
        <label for="avatar_file"><?php echo Lang::__("login.uploadavatar.label.avatarfile");?></label>
        <input type="file" name="avatar_file" required />
        <!-- max size 5 MB (as many people directly upload high res pictures from their digital cameras) -->
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        <input name="submit" type="submit" value="<?php echo Lang::__("login.uploadavatar.submit");?>" />
    </form>
</div>
