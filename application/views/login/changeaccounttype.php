<div class="content">
    <h1><?php echo Lang::__("login.changeaccounttype.title");?></h1>

<?php echo Lang::__("login.changeaccounttype.content");?>
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <h2><?php echo Lang::__("login.changeaccounttype.youraccounttype");?>&nbsp;<?php echo Session::get('user_account_type'); ?></h2>
    <!-- basic implementation for two account type: type 1 and type 2 -->
    <?php if (Session::get('user_account_type') == 1) { ?>
    <form action="<?php echo URL; ?>login/changeaccounttype_action" method="post">
        <label><?php echo Lang::__("login.changeaccounttype.label.upgrademyaccount");?></label>
        <input type="submit" name="user_account_upgrade" value="<?php echo Lang::__("login.changeaccounttype.submit.upgrademyaccount");?>" />
    </form>
    <?php } elseif (Session::get('user_account_type') == 2) { ?>
    <form action="<?php echo URL; ?>login/changeaccounttype_action" method="post">
        <label><?php echo Lang::__("login.changeaccounttype.label.downgrademyaccount");?></label>
        <input type="submit" name="user_account_downgrade" value="<?php echo Lang::__("login.changeaccounttype.submit.downgrademyaccount");?>" />
    </form>
    <?php } ?>
</div>
