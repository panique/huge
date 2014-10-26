<div class="content">
    <h1><?php echo Lang::__("login.verify.title");?>Verification</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <a href="<?php echo URL; ?>login/index"><?php echo Lang::__("login.backlink");?></a>
</div>
