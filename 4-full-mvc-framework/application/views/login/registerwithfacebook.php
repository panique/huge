<div class="content">

    <?php
    if (isset($this->errors)) {
        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }
    }
    ?>

    <?php ?>

</div>
