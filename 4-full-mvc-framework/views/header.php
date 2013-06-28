<!doctype html>
<html>
<head>
	<title>Test</title>
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/custom.js"></script>
</head>
<body>

<div class="header">
	
        <!-- Navigation (left side) -->
        <div>
            HEADER 
            
            <a href="<?php echo URL; ?>index">Index</a>
            <a href="<?php echo URL; ?>help">Help</a>
            
            <?php if (Session::get('user_logged_in') == true):?>
            
                    <a href="<?php echo URL; ?>dashboard">Dashboard</a>	
                    <a href="<?php echo URL; ?>note">My Notes</a>
                    <a href="<?php echo URL; ?>login/editusername">Edit my username</a>
                    <a href="<?php echo URL; ?>login/edituseremail">Edit my email</a>
                    <a href="<?php echo URL; ?>login/logout">Logout</a>
                    
                    Hello <?php echo Session::get('user_name'); ?>, you are logged in!
                    
                    <?php echo Session::get('user_gravatar_image_tag'); ?>
                    
            <?php else: ?>
                
                <a href="<?php echo URL; ?>login/register">Register</a>
                <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
                <a href="<?php echo URL; ?>login">Login</a>
                
            <?php endif; ?>                    
                    
        </div>
        
</div>
	
	