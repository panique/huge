<div class="content">

    <h1>Verification</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<h3>'.$error.'</h3>';
        }

    }
    
    ?>
    
    <a href="<?php echo URL; ?>/login">Go to login</a>
    
</div>