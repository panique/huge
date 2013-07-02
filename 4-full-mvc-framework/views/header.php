<!doctype html>
<html>
<head>
	<title>Test</title>
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/custom.js"></script>
</head>
<body>
    
    <div style="margin:20px 10px 10px 10px; padding: 10px; color: red; border:1px solid red;">
        DEBUG: you are in the view: <?php echo $filename; ?>
    </div>

    <div class="header">

        <div style="float:left;">
        <ul id="menu">
            <li>
                <a <?php if ($this->checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>index/index">Index</a>
            </li>
            <li>
                <a <?php if ($this->checkForActiveController($filename, "help")) { echo ' class="active" '; } ?>  href="<?php echo URL; ?>help/index">Help</a>
            </li>
            <?php if (Session::get('user_logged_in') == true):?>
            <li>
                <a <?php if ($this->checkForActiveController($filename, "dashboard")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>dashboard/index">Dashboard</a>	
            </li>   
            <?php endif; ?>                    
            <?php if (Session::get('user_logged_in') == true):?>
            <li>
                <a <?php if ($this->checkForActiveController($filename, "note")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>note/index">My Notes</a>
            </li>   
            <?php endif; ?>                    


            <?php if (Session::get('user_logged_in') == true):?>
                <li>
                    <a <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> href="#">MY ACCOUNT</a>
                    <ul class="sub-menu">
                        <li>
                            <a <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/editusername">Edit my username</a>
                        </li>
                        <li>
                            <a <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/edituseremail">Edit my email</a>
                        </li>
                        <li>
                            <a <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/logout">Logout</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>          

            <!-- for not logged in users -->
            <?php if (Session::get('user_logged_in') == false):?>

                <li>
                    <a <?php if ($this->checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/index">Login</a>
                </li>  
                <li>
                    <a <?php if ($this->checkForActiveControllerAndAction($filename, "login/register")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/register">Register</a>
                </li>         
                <li>
                    <a <?php if ($this->checkForActiveControllerAndAction($filename, "login/requestpasswordreset")) { echo ' class="active" '; } ?>href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
                </li>

            <?php endif; ?>

        </ul>   
        </div>

        <?php if (Session::get('user_logged_in') == true): ?>
            <div style="float:right;">
                <div class="namebox">
                    Hello <?php echo Session::get('user_name'); ?> !
                </div>
                <div class="avatar">
                    <?php echo Session::get('user_gravatar_image_tag'); ?>
                </div>
            </div>
        <?php endif; ?>

        <div style="clear: both;"></div>

    </div>	
	