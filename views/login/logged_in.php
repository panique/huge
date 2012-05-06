<h1>Member Area</h1>
<p>Thanks for logging in! You are <b><?php echo $_SESSION['user_name']; ?></b> and your email address is <b><?php echo $_SESSION['user_email']; ?></b>.</p>

<?php //var_dump($_SESSION); ?>

<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=logout">Logout</a>