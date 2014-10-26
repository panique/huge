<?php header("Content-type: text/html; charset=utf-8");?>
<!doctype html>
<html <?php if (isset($_SESSION['current_language'])) echo 'lang="'.Lang::getISOLanguageCode($_SESSION['current_language']).'"'?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo APP_NAME;?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/reset.css" />
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/style.css" />
    <!-- in case you wonder: That's the cool-kids-protocol-free way to load jQuery -->
    <script type="text/javascript" src="//code.jquery.com/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?php echo URL; ?>public/js/application.js"></script>
<script type="text/javascript">
        $(function () {
            $("#language_selector").on("change keyup", function () {
            	/*alert( $(this).find("option:selected").attr('value') );*/
            	$("#language_form").submit();
            });
        });
    </script>
</head>
<body> 

    <div class="debug-helper-box">
       <?php echo Lang::__("debug.message")." : ".Lang::__("you.are.here")." : ".$filename; ?>
    </div>

    <div class='title-box'>
        <a href="<?php echo URL; ?>"><?php echo APP_NAME;?></a>
    </div> 
    
    <div class='select-language-box'>
    	<?php 
    	echo Lang::getLanguageHTMLForm($_SESSION['current_language'],
    										"language_form",
    										"language_selector",
    										"GET",
    										URL."index/language_form_action",
    										"language_selector",
											((isset($_SERVER['QUERY_STRING'])&& $_SERVER['QUERY_STRING']!="")?str_replace("url=","",$_SERVER['QUERY_STRING']):$filename));?>
    </div>

    <div class="header">
        <div class="header_left_box">
        <ul id="menu">
            <li <?php if ($this->checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> >
                <a href="<?php echo URL; ?>index/index"><?php echo Lang::__("menu.index");?></a>
            </li>
            <li <?php if ($this->checkForActiveController($filename, "help")) { echo ' class="active" '; } ?> >
                <a href="<?php echo URL; ?>help/index"><?php echo Lang::__("menu.help");?></a>
            </li>
            <li <?php if ($this->checkForActiveController($filename, "overview")) { echo ' class="active" '; } ?> >
                <a href="<?php echo URL; ?>overview/index"><?php echo Lang::__("menu.overview");?></a>
            </li>
            <?php if (Session::get('user_logged_in') == true):?>
            <li <?php if ($this->checkForActiveController($filename, "dashboard")) { echo ' class="active" '; } ?> >
                <a href="<?php echo URL; ?>dashboard/index"><?php echo Lang::__("menu.dashboard");?></a>
            </li>
            <?php endif; ?>
            <?php if (Session::get('user_logged_in') == true):?>
            <li <?php if ($this->checkForActiveController($filename, "note")) { echo ' class="active" '; } ?> >
                <a href="<?php echo URL; ?>note/index"><?php echo Lang::__("menu.notes");?></a>
            </li>
            <?php endif; ?>

            <?php if (Session::get('user_logged_in') == true):?>
                <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo URL; ?>login/showprofile"><?php echo Lang::__("menu.login");?></a>
                    <ul class="sub-menu">
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/changeaccounttype"><?php echo Lang::__("menu.changeaccounttype");?></a>
                        </li>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/editpassword"><?php echo Lang::__("menu.editpasword");?></a>
                        </li>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/uploadavatar"><?php echo Lang::__("menu.uploadavatar");?></a>
                        </li>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/editusername"><?php echo Lang::__("menu.editusername");?></a>
                        </li>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/edituseremail"><?php echo Lang::__("menu.edituseremail");?></a>
                        </li>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/logout"><?php echo Lang::__("menu.logout");?></a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- for not logged in users -->
            <?php if (Session::get('user_logged_in') == false):?>
                <li <?php if ($this->checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo URL; ?>login/index"><?php echo Lang::__("menu.login");?></a>
                </li>
                <li <?php if ($this->checkForActiveControllerAndAction($filename, "login/register")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo URL; ?>login/register"><?php echo Lang::__("menu.register");?></a>
                </li>
            <?php endif; ?>
        </ul>
        </div>

        <?php if (Session::get('user_logged_in') == true): ?>
            <div class="header_right_box">
                <div class="namebox">
                    <?php echo Lang::__("header.hello");?> <?php echo Session::get('user_name'); ?> !
                </div>
                <div class="avatar">
                    <?php if (USE_GRAVATAR) { ?>
                        <img src='<?php echo Session::get('user_gravatar_image_url'); ?>'
                             style='width:<?php echo AVATAR_SIZE; ?>px; height:<?php echo AVATAR_SIZE; ?>px;' />
                    <?php } else { ?>
                        <img src='<?php echo Session::get('user_avatar_file'); ?>'
                             style='width:<?php echo AVATAR_SIZE; ?>px; height:<?php echo AVATAR_SIZE; ?>px;' />
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="clear-both"></div>
    </div>
