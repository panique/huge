<div class="content">
    <div class="page-header text-center">
        <h1>ProfileController/showProfile/:id<small></small></h1>
    </div>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <div class="well">
        <h3>What happens here ?</h3>
        This controller/action/view shows all public information about a certain user.
    </div>

    <?php if ($this->user) { ?>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>User's email</td>
                        <td>Activated ?</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="<?= ($this->user->user_active == 0 ? 'inactive' : 'active'); ?>">
                        <td><?= $this->user->user_id; ?></td>
                        <td class="avatar">
                            <?php if (isset($this->user->user_avatar_link)) { ?>
                                <img src="<?= $this->user->user_avatar_link; ?>" />
                            <?php } ?>
                        </td>
                        <td><?= $this->user->user_name; ?></td>
                        <td><?= $this->user->user_email; ?></td>
                        <td><?= ($this->user->user_active == 0 ? 'No' : 'Yes'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>

</div>

