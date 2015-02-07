<div class="content">
    <div class="page-header text-center">
        <h1>LoginController/showProfile<small></small></h1>
    </div>
    
    
            <nav class="navbar navbar-default ">
        <div class="container-fluid">
          <div class="navbar-header">
            <!--  potentially put the project name here config file?   <a class="navbar-brand" href="#">Project name</a>-->
          </div>
            <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav nav-pills nav-justified">
              <li <?php if (View::checkForActiveController($filename, "")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>login/changeAccountType">Upgrade!</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>login/changePassword">Change Password!</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>login/edituserEmail">Edit Email!</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>login/editUsername">Edit User Name!</a>  </li>
              <li <?php if (View::checkForActiveController($filename, "")) { echo ' class="active" '; } ?> > <a href="<?php echo Config::get('URL'); ?>login/uploadAvatar">Upload Avatar!</a>  </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <div class="well">
        <h3>Your profile</h3>
        <div>Your username: <?= $this->user_name; ?></div>
        <div>Your email: <?= $this->user_email; ?></div>
        <div>Your avatar image:
            <?php if (Config::get('USE_GRAVATAR')) { ?>
                Your gravatar pic (on gravatar.com): <img src='<?= $this->user_gravatar_image_url; ?>' />
            <?php } else { ?>
                Your avatar pic (saved locally): <img src='<?= $this->user_avatar_file; ?>' />
            <?php } ?>
        </div>
        <div>Your account type is: <?= $this->user_account_type; ?></div>
    </div>
</div>