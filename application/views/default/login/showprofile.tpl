<div class="content">
    <h1>Your profile</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div>
        Your username: <?php echo Session::get('user_name'); ?>
    </div>
    <div>
        Your email: <?php echo Session::get('user_email'); ?>
    </div>
    <div>
        Your gravatar pic (on gravatar.com): <img src='<?php echo Session::get('user_gravatar_image_url'); ?>' />
    </div>
    <div>
        Your avatar pic (saved on local server): <img src='<?php echo Session::get('user_avatar_file'); ?>' />
    </div>
    <div>
        Your account type is: <?php echo Session::get('user_account_type'); ?>
    </div>
</div>
