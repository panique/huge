<div class="content">
    <h1>Upload an avatar</h1>

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
    
    <form action="<?php echo URL; ?>login/uploadavatar_action" method="post" enctype="multipart/form-data">
        <label for="avatar_file">Select an avatar image from your hard-disk (will be scaled to 44x44 px):</label>
        <input type="file" name="avatar_file" required />
        <!-- max size 5 MB (as many people directly upload high res pictures from their digital cameras) -->
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        <input name="submit" type="submit" value="Upload image" />
    </form>
</div>
