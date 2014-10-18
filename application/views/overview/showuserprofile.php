<div class="content">
    <h1><?php echo Lang::__("overview.showuserprofile.title");?></h1>
    <p>
        <?php echo Lang::__("overview.showuserprofile.introduction");?>
    </p>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <?php if ($this->user) { ?>
        <p>
			<span style="color: red;"><?php echo Lang::__("overview.showuserprofile.note_email");?></span>        
            <table class="overview-table">
		<tr>
        	<th><?php echo Lang::__("overview.showuserprofile.label.userid");?></th>
        	<th><?php echo Lang::__("overview.showuserprofile.label.useravatar");?></th>
        	<th><?php echo Lang::__("overview.showuserprofile.label.username");?></th>
        	<th><?php echo Lang::__("overview.showuserprofile.label.useremail");?></th>
        	<th><?php echo Lang::__("overview.showuserprofile.label.useractive");?></th>
        </tr>
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
       	        echo '<td>'.($this->user->user_active?Lang::__("overview.showuserprofile.useractive"):Lang::__("overview.showuserprofile.userinactive")).'</td>';
                echo "</tr>";
            ?>
            </table>
        </p>
    <?php } ?>
</div>
