<!doctype html>
<html>
<head>
    <!-- META -->
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- wrapper, to center website -->
    <div class="container">
        
        <!-- logo -->
        <a href="<?php echo Config::get('URL')?>index"><div class="logo"></div></a>

        <!-- navigation -->
        <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <!--  potentially put the project name here config file?   <a class="navbar-brand" href="#">Project name</a>-->
          </div>
            
            <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li <?php if (View::checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>index/index">Home</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "profile")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>profile/index">Profiles</a>  </li>
              <?php if (Session::userIsLoggedIn()) : ?>
              <li <?php if (View::checkForActiveController($filename, "dashboard")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>dashboard/index">Dashboard</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "note")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>note/index">Note</a>  </li>
              <?php endif; ?>
      
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php if (Session::userIsLoggedIn()) { ?>
                <li><a href="<?php echo Config::get('URL'); ?>login/showprofile">Welcome <?php echo Session::get('user_name'); ?> !</a></li>
                <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>Login/ShowProfile">My Account</a>  </li>
                <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>Login/Logout">Logout</a>  </li>
            <?php }else{ ?>
              <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>Login/index">Login</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>Login/register">Register</a>  </li>
            <?php } ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
        