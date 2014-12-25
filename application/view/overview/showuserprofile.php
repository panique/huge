<div class="content">
    <h1>A public user profile</h1>
    <p>This controller/action/view shows all public information about a certain user.</p>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <?php if ($this->user) { ?>
        <p>
            <span style="color: red;">NOTE: be sure NOT to show email addresses of users in a real app. This is just a demo.</span>
            <table class="overview-table">
            <?php

                if ($this->user->user_active == 0) {
                    echo '<tr class="inactive">';
                } else {
                    echo '<tr class="active">';
                }

                echo '<td>'.$this->user->user_id.'</td>';
                echo '<td class="avatar"><img src="'.$this->user->user_avatar_link.'" /></td>';
                echo '<td>'.$this->user->user_name.'</td>';
                echo '<td>'.$this->user->user_email.'</td>';
                echo '<td>Active: '.$this->user->user_active.'</td>';
                echo "</tr>";
            ?>
            </table>
        </p>
    <?php } ?>
</div>
