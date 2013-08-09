<div class="content">

    <h1>Your profile</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<div class="system_message">'.$error.'</div>';
        }

    }

    ?>

    <div>
        Your username: <?php echo Session::get('user_name'); ?>
    </div>
                
    <div>
        Your gravatar pic: <?php echo Session::get('user_gravatar_image_tag'); ?>
    </div>     
    
</div>