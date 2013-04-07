<?php //Simple Sexy PHP Login Script      // @license GNU General Public License Version 3 
	include_once('classes/PHPlogin.php'); 
	$PHPlogin = new PHPlogin();
	include_once('views/header/header.php');	
	$PHPlogin->display();
?>




<!-- YOUR STUFF HERE  -->




<!-- message example-->
<?
$PHPlogin->add_message('Green and happy message');
$PHPlogin->add_error('Red and bad message');
?>
<!-- end example -->





<?include('views/footer/footer.php')?>