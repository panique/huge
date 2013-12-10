<?php
// include html header and display php-login message/error
include('header.php');

// if you need users's information, just put them into the $_SESSION variable and output them here

echo $phplogin_lang['you_are_logged_in_as'] . $_SESSION['user_name'] ."<br />\n";
//echo $login->user_gravatar_image_url;
echo $phplogin_lang['profile_picture'] .'<br/>'. $login->user_gravatar_image_tag;

?>
<div>
	<a href="index.php?logout"><?php echo $phplogin_lang['logout']; ?></a>
	<a href="edit.php"><?php echo $phplogin_lang['edit_user_data']; ?></a>
</div>

<?php
// include html footer
include('footer.php');
