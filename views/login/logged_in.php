
<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->
        <div id="PHPlogin" style="position:absolute; top:0; right:100px; background-color:#fff; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25); width:250px; height:50px;">
            <div id="login_avatar" style="width:50px; height:50px; float:left; margin:0; background-image: url('<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $_SESSION['user_email'] ) ) ) . "?d=mm&s=50"; ?>')">
            </div>
            <div style="width: 110px; height: 50px; float:left; margin:0; font-family: 'Droid Sans', sans-serif; color:#666666; font-size:12px; border:0; height:100%; line-height: 50px; padding-left:20px; padding-right: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                Hey, <?php echo $_SESSION['user_name']; ?>
            </div>
            <div class="login_logout">
                <a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>?logout" style="width:49px; height:19px; padding-top: 31px; display:block; text-align: center; font-size:10px; font-family: 'Droid Sans', sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" >Logout</a>
            </div>            
        </div>
