<div class="content">
    <h1><?php echo Lang::__("overview.index.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <p>
       <?php echo Lang::__("overview.index.introduction");?>
    </p>

    <p>
		<span style="color: red;"><?php echo Lang::__("overview.index.note_email");?></span>        
        <table class="overview-table">
 		<tr>
        	<th><?php echo Lang::__("overview.index.label.userid");?></th>
        	<th><?php echo Lang::__("overview.index.label.useravatar");?></th>
        	<th><?php echo Lang::__("overview.index.label.username");?></th>
        	<th><?php echo Lang::__("overview.index.label.useremail");?></th>
        	<th><?php echo Lang::__("overview.index.label.useractive");?></th>
        	<th><?php echo Lang::__("overview.index.label.userlink");?></th>
        </tr>
        <?php

        foreach ($this->users as $user) {

            if ($user->user_active == 0) {
                echo '<tr class="inactive">';
            } else {
                echo '<tr class="active">';
            }

            echo '<td>'.$user->user_id.'</td>';
            echo '<td class="avatar">';

            if (isset($user->user_avatar_link)) {
                echo '<img src="'.$user->user_avatar_link.'" />';
            }

            echo '</td>';
            echo '<td>'.$user->user_name.'</td>';
            echo '<td>'.$user->user_email.'</td>';
            echo '<td>Active: '.($user->user_active?Lang::__("overview.index.useractive"):Lang::__("overview.index.userinactive")).'</td>';
            echo '<td><a href="'.URL.'overview/showuserprofile/'.$user->user_id.'">'.Lang::__("overview.index.userlink").'</a></td>';
            echo "</tr>";
        }

        ?>
        </table>
    </p>
</div>
