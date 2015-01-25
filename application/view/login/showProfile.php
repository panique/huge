<div class="content">
    <h1>Your profile</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div>Your username: <?= $this->user_name; ?></div>
    <div>Your email: <?= $this->user_email; ?></div>
    <div>Your avatar image:
        <?php if (Config::get('USE_GRAVATAR')) { ?>
            Your gravatar pic (on gravatar.com): <img src='<?= $this->user_gravatar_image_url; ?>' />
        <?php } else { ?>
            Your avatar pic (saved locally): <img src='<?= $this->user_avatar_file; ?>' />
        <?php } ?>
    </div>
    <div>Your account type is: <?= $this->user_account_type; ?></div>
</div>
