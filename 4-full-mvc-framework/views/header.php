<!doctype html>
<html>
<head>
	<title>Test</title>
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/custom.js"></script>
</head>
<body>
    
DEBUG: you are in the view: <?php echo $filename; ?>

<div class="header">
	       
    <div style="float:left;">
    <ul id="menu">
        <li>
            <a <?php if ($this->activeNavigationElementChecker($filename, "index")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>index/index">Index</a>
        </li>
        <li>
            <a <?php if ($this->activeNavigationElementChecker($filename, "help")) { echo ' class="active" '; } ?>  href="<?php echo URL; ?>help/index">Help</a>
        </li>
        <?php if (Session::get('user_logged_in') == true):?>
        <li>
            <a <?php if ($this->activeNavigationElementChecker($filename, "dashboard")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>dashboard/index">Dashboard</a>	
        </li>   
        <?php endif; ?>                    
        <?php if (Session::get('user_logged_in') == true):?>
        <li>
            <a <?php if ($this->activeNavigationElementChecker($filename, "note")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>note/index">My Notes</a>
        </li>   
        <?php endif; ?>                    


        <?php if (Session::get('user_logged_in') == true):?>
            <li>
                <a <?php if ($this->activeNavigationElementChecker($filename, "login")) { echo ' class="active" '; } ?> href="#">MY ACCOUNT</a>
                <ul class="sub-menu">
                    <li>
                        <a <?php if ($this->activeNavigationElementChecker($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/editusername">Edit my username</a>
                    </li>
                    <li>
                        <a <?php if ($this->activeNavigationElementChecker($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/edituseremail">Edit my email</a>
                    </li>
                    <li>
                        <a <?php if ($this->activeNavigationElementChecker($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/logout">Logout</a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>          

        <!-- for not logged in users -->
        <?php if (Session::get('user_logged_in') == false):?>

            <li>
                <a <?php if ($this->activeNavigationElementChecker($filename, "login")) { echo ' class="active" '; } ?> href="<?php echo URL; ?>login/index">Login</a>
            </li>  
            <li>
                <a href="<?php echo URL; ?>login/register">Register</a>
            </li>         
            <li>
                <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
            </li>

        <?php endif; ?>
            
    </ul>   
    </div>
    
    <div style="float:right;">
        <div class="namebox">
            Hello <?php echo Session::get('user_name'); ?> !
        </div>
        <div class="avatar">
            <?php echo Session::get('user_gravatar_image_tag'); ?>
        </div>
    </div>
    
    <div style="clear: both;"></div>
        
</div>
	
	