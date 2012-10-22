<?php include('views/header/header.php'); ?>

<div class="login_wrapper">
    <?php

    if ($login->errors) {
        foreach ($login->errors as $error) {
            
    ?>              
    <div class="login_message error">
        <?php echo $error; ?>
    </div>
    <?php
    
        }
    }
    
    if ($login->messages) {
        foreach ($login->messages as $message) {
    ?>
    <div class="login_message success">
        <?php echo $message; ?>
    </div>              
    <?php
    
        }
    }

    ?>    
    
    <?php if (!$login->registration_successful) { ?>
    
    <form method="post" action="index.php?register" name="registerform" id="registerform">
    <div class="login" style="height:250px;">
        <div id="login_avatar_wrapper" style="width: 125px; height: 250px; float:left; margin:0;">
            <div id="login_avatar" class="standard_avatar" style="width: 125px; height: 125px; float:left; margin:0;">
                <!--<img id="login_avatar" src="views/img/ani_avatar_static_01.png" style="width:125px; height:125px;" />-->
            </div>
            <div style="width: 124px; height: 125px; float:right; margin:0; border-right: 1px solid #e6e6e6;">
            </div>
        </div>
        <div style="width: 250px; height: 125px; float:left; margin:0;">
            <div style="width: 250px; height: 62px; float:left; margin:0; border-bottom: 1px solid #e6e6e6;">
                <input id="login_input_username" class="login_input" type="text" name="user_name" value="Username" />
            </div>
            <div style="width: 250px; height: 61px; float:left; margin:0; border-bottom: 1px solid #e6e6e6;">
                <input id="login_input_email" class="login_input" type="text" name="user_email" value="eMail" />
            </div>
            <div style="width: 250px; height: 62px; float:left; margin:0; border-bottom: 1px solid #e6e6e6;">
                <input id="login_input_password_new_label" class="login_input" type="text" value="Password" />
                <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" autocomplete="off" />
            </div>            
            <div style="width: 250px; height: 61px; float:left; margin:0; border-bottom: 1px solid #e6e6e6;">
                <input id="login_input_password_repeat_label" class="login_input" type="text" value="Repeat Password" />
                <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" autocomplete="off" />
            </div>          
        </div>
        <div style="width: 124px; height: 250px; float:left; margin:0; border-left: 1px solid #e6e6e6;">
            <div class="login_submit_register">
                <input type="submit"  name="register" style="width:124px; height:250px; padding-top: 60px;  text-align: center; font-size:11px; font-family: 'Droid Sans', sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" value="Register" />            
            </div>        
        </div>
    </div>    
    <div style="width:500px; height: 40px; line-height: 40px; text-align: right; color:#ccc; font-size:11px; font-family: 'Droid Sans', sans-serif; ">
        <a class="login_link" href="index.php">Back to Login Page</a>
    </div>
    </form>
    
    <?php } ?>
    
</div>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->

<?php include('views/footer/footer.php'); ?>