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
class Captcha
{
    /**
     * generates the captcha string
     */
    public function generateCaptcha()
    {
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
     * TODO: is this valid coding style as this returns something "binary" (correct me please if i'm talking bullshit).
     */
    public function showCaptcha()
    {
        // get letters from SESSION, split them, create array of letters
        $letters = str_split($_SESSION['captcha']);
        
        // begin to create the image with PHP's GD tools
        $image = imagecreatetruecolor(150, 70);
        // TODO: error handling if creating images fails
        //or die("Cannot Initialize new GD image stream");
        
        $background = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $background);

        // create letters. for more info on how this works, please
        // @see php.net/manual/en/function.imagefttext.php
        $i = 0;
        foreach ($letters as $letter) {
            $text_color = imagecolorallocate($image, rand(0,100), rand(10,100), rand(0,100));
            imagefttext($image, 35, rand(-10, 10), 20+($i*30) + rand(-5, +5), 35 + rand(10, 30),
                $text_color, CAPTCHA_FONT_PATH, $letter);
            $i++;
        }

        // send http-header to prevent image caching (so we always see a fresh captcha image)
        header('Content-type: image/png');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, proxy-revalidate');
        
        // send image to browser, destroy image from php "cache"
        imagepng($image);
        imagedestroy($image);
    }
    
    /**
     * simply checks if the entered captcha is the same like the one from the rendered image (=SESSION)
     */
    public function checkCaptcha()
    {
        if (isset($_POST["captcha"]) AND (strtolower($_POST["captcha"]) == strtolower($_SESSION['captcha']))) {
            return true;
        }
        // default return
        return false;
    }
}
