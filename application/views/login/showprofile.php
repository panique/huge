<div class="content">
    <h1><?php echo Lang::__("login.showprofile.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div>
        <?php echo Lang::__("login.showprofile.username");?>&nbsp;<?php echo Session::get('user_name'); ?>
    </div>
    <div>
        <?php echo Lang::__("login.showprofile.useremail");?>&nbsp;<?php echo Session::get('user_email'); ?>
    </div>
    <div>
        <?php echo Lang::__("login.showprofile.useravatar");?>
        <?php // if usage of gravatar is activated show gravatar image, else show local avatar ?>
        <?php if (USE_GRAVATAR) { ?>
            <?php echo Lang::__("login.showprofile.gravatarpic");?>&nbsp;<img src='<?php echo Session::get('user_gravatar_image_url'); ?>' />
        <?php } else { ?>
            <?php echo Lang::__("login.showprofile.avatarpic");?>&nbsp;<img src='<?php echo Session::get('user_avatar_file'); ?>' />
        <?php } ?>
    </div>
    <div>
        <?php echo Lang::__("login.showprofile.useraccounttype");?>&nbsp;<?php echo Session::get('user_account_type'); ?>
    </div>
</div>
