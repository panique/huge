<?php

/**
 * a little bit dirty, but it works:
 * This file generates a captcha string, writes it into the $_SESSION['captcha']
 * and renders a fresh captcha graphic file to the browser. This is oldschool-non-PHP,
 * but it does the job.
 * 
 * In the views you can use this by saying: 
 * <img src="tools/showCaptcha.php" />
 * 
 * Check if the typed captcha is correct by saying:
 * if ($_POST["captcha"] == $_SESSION['captcha']) { ... } else { ... }
 *  
 */

session_start();

// create set of usage characters
$letters = array_merge( range('A', 'Z') , range(2, 9) );
unset($letters[array_search('O', $letters)]);
unset($letters[array_search('Q', $letters)]);
unset($letters[array_search('I', $letters)]);
unset($letters[array_search('5', $letters)]);
unset($letters[array_search('S', $letters)]);
shuffle($letters);
$selected_letters = array_slice($letters, 0, 4);
$secure_text = implode('', $selected_letters);

// write the 4 selected letters into a SESSION variable
$_SESSION['captcha'] = $secure_text;

// get letters from SESSION, split them, create array of letters
$letters = str_split($_SESSION['captcha']);

// begin to create the image with PHP's GD tools
$im = imagecreatetruecolor(150, 70);
// TODO: error handling if creating images fails
//or die("Cannot Initialize new GD image stream");

$bg = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $bg);

// create background with 1000 short lines
/*
for($i=0;$i<1000;$i++) {
    $lines = imagecolorallocate($im, rand(200, 220), rand(200, 220), rand(200, 220));
    $start_x = rand(0,150);
    $start_y = rand(0,70);
    $end_x = $start_x + rand(0,5);
    $end_y = $start_y + rand(0,5);
    imageline($im, $start_x, $start_y, $end_x, $end_y, $lines);
}
*/

// create letters. for more info on how this works, please
// @see php.net/manual/en/function.imagefttext.php
// TODO: put the font path into the config
$i = 0;
foreach ($letters as $letter) {
    $text_color = imagecolorallocate($im, rand(0,100), rand(10,100), rand(0,100));
    // font-path relative to this file
    imagefttext($im, 35, rand(-10, 10), 20+($i*30) + rand(-5, +5), 35 + rand(10, 30),  $text_color, 'fonts/times_new_yorker.ttf', $letter);
    $i++;
}

// send http-header to prevent image caching (so we always see a fresh captcha image)
header('Content-type: image/png');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, proxy-revalidate');

// send image to browser, destroy image from php "cache"
imagepng($im);
imagedestroy($im);
