<?php include('views/header/header.php'); ?>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->


        <!-- 
        If you want to make this "you are logged in"-box wider, simply ...        
        -->

        <div style="position:absolute; top:0; right:100px; background-color:#fff; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25); width:250px; height:50px;">
            <div id="login_avatar" style="width:50px; height:50px; float:left; margin:0; background-image: url('<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $_SESSION['user_email'] ) ) ) . "?d=mm&s=50"; ?>')">
                <!--<img id="login_avatar" src="views/img/ani_avatar_static_01.png" style="width:125px; height:125px;" />-->
            </div>
            <div style="width: 110px; height: 50px; float:left; margin:0; font-family: 'Droid Sans', sans-serif; color:#666666; font-size:12px; border:0; height:100%; line-height: 50px; padding-left:20px; padding-right: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                Hey, <?php echo $_SESSION['user_name']; ?>
            </div>
            <div class="login_logout">
                <a href="index.php?logout" style="width:49px; height:19px; padding-top: 31px; display:block; text-align: center; font-size:10px; font-family: 'Droid Sans', sans-serif; color:#666666; border:0; background: transparent; cursor: pointer;" >Logout</a>
            </div>            
            
            
            <!--
                <div style="width:100%; height:30px;">
                        <div style="float:left; height:30px;">
                                <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $_SESSION['user_email'] ) ) ) . "?d=mm&s=40"; ?>" style="width:30px; height:30px; border-radius:15px; border:1px solid #ccc;" />                                
                        </div>		
                        <div style="float:left; height:30px; margin-left:10px; line-height:30px;">
                            <div style="font-size:12px; font-weight:normal; color:#777;">Hey, <?php echo $_SESSION['user_name']; ?> (<span style="font-size:12px; color:#aaa;"><?php echo substr($_SESSION['user_email'], 0, 10); ?>...</span>). You're logged in. <a href="<?php echo $_SERVER['PHP_SELF']; ?>?logout">(Logout)</a>   </div>
                        </div>
                </div
                -->
        </div>

<?php include('views/footer/footer.php'); ?>