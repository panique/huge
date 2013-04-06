<?php !defined('PHPLOGIN_LOCATION') ? define('PHPLOGIN_LOCATION', ''): TRUE ; ?>

<!--PHPLogin css -->
<link href='<?php echo PHPLOGIN_LOCATION; ?>views/style/style.css' rel='stylesheet' type='text/css' />
<!--PHPLogin jQuery und md5 plugin (necessary to get gravatar-avatar pictures) -->
<script src="<?=PHPLOGIN_LOCATION?>views/js/jquery-1.8.1.min.js" type="text/javascript"></script>
<script src="<?=PHPLOGIN_LOCATION?>views/js/jquery.md5.min.js" type="text/javascript"></script>
<!--PHPLogin specific JavaScript (no logic stuff here, just css/layout actions) -->
<script src="<?=PHPLOGIN_LOCATION?>views/js/beautiful_login.js" type="text/javascript"></script>       


