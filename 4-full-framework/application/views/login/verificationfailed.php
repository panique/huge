<div class="content">
    <h1>Verification failed</h1>

    <?php
    if (isset($this->errors)) {
        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }
    }
    ?>
    
    <a href="<?php echo URL; ?>login/index">Go to login</a>
</div>
