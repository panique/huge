<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP-login</title>
</head>
<body>
<div>
<?php
// if you need users's information, just put them into the $_SESSION variable and output them here

echo $phplogin_lang['You are logged in as'] . $_SESSION['user_name'] ."<br />\n";
//echo $login->user_gravatar_image_url;
echo $phplogin_lang['Profile picture'] .'<br/>'. $login->user_gravatar_image_tag;

?>
</div>

<div>
	<a href="index.php?logout"><?php echo $phplogin_lang['Logout']; ?></a>
	<a href="edit.php"><?php echo $phplogin_lang['Edit user data']; ?></a>
</div>
</body>
</html>
