<div class="content">
    <h1>Change your username</h1>

    <?php
    if (isset($this->feedback["success"])) {
        foreach ($this->feedback["success"] as $feedback) {
            echo '<div class="feedback success">'.$feedback.'</div>';
        }
    } elseif (isset($this->feedback["error"])) {
        foreach ($this->feedback["error"] as $feedback) {
            echo '<div class="feedback error">'.$feedback.'</div>';
        }
    }
    ?>

    <form action="<?php echo URL; ?>login/editusername_action" method="post">
        <label>New username</label>
        <input type="text" name="user_name" required />
        <input type="submit" value="Submit" />
    </form>
    
</div>
