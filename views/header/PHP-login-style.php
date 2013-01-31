
<?php 
if (! defined('PHPLOGIN_LOCATION')){
	define('PHPLOGIN_LOCATION', '');	
}
?>

<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
<!-- css -->
<link href='<?php echo PHPLOGIN_LOCATION; ?>views/css/style.css' rel='stylesheet' type='text/css' /><!-- the path is always relative to index.php -->
<!-- jQuery und md5 plugin (necessary to get gravatar-avatar pictures) -->
<script src="<?=PHPLOGIN_LOCATION?>views/js/jquery-1.8.1.min.js" type="text/javascript"></script>
<script src="<?=PHPLOGIN_LOCATION?>views/js/jquery.md5.min.js" type="text/javascript"></script>
<!-- Simple PHP Login specific JavaScript (no logic stuff here, just css/layout actions) -->
<script src="<?=PHPLOGIN_LOCATION?>views/js/beautiful_login.js" type="text/javascript"></script>       


