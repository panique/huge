<div class="content">
    <h1><?php echo Lang::__("application.summary.title",APP_NAME);?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages();  ?>

	<?php echo Lang::__("application.summary.content",APP_NAME);?>
</div>
