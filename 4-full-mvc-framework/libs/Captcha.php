<?php

/*
 * The Captcha class
 * 
 * Creates, renders and checks the captcha.
 * This class uses the free, "dirty" Times New Yorker font
 * @see http://www.dafont.com/times-new-yorker.font
 * 
 * This class is also inspired by https://github.com/dgmike/captcha
 * 
 */
class Captcha {
    
    /**
     * generates the captcha string
     */
    public function generateCaptcha() {

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
    }
    
    /**
     * renders an image to the browser
     * 
     * TODO: this is not really good coding style, as this does return
     * something "binary" (correct me please if i'm talking bullshit).
     * maybe there's a cleaner method to do this ? all captcha scripts
     * i checked are doing it like this
     */
    public function showCaptcha() {
                
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
            // font-path relative to the index.php of the entire app
            imagefttext($im, 35, rand(-10, 10), 20+($i*30) + rand(-5, +5), 35 + rand(10, 30),  $text_color, 'tools/fonts/times_new_yorker.ttf', $letter);
            $i++;
        }

        // send http-header to prevent image caching (so we always see a fresh captcha image)
        header('Content-type: image/png');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, proxy-revalidate');
        
        // send image to browser, destroy image from php "cache"
        imagepng($im);
        imagedestroy($im);
        
    }
    
    /**
     * simply checks if the entered captcha is the same like the one from the rendered image (=SESSION)
     */
    public function checkCaptcha() {
        
        // a little bit simple, but it will work for a basic captcha system
        // TODO: write stuff like that simpler with ternary operators
        if ($_POST["captcha"] == $_SESSION['captcha']) {
            
            return true;            
            
        } else {
            
            return false;
            
        }
        
    }

}