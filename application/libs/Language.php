<?php

/**
 * Language class
 *
 * 
 */
class Language {

    /**
     * sets language file
     */

    public static function init() {
        $lang = DEFAULT_LANGUAGE;
        
        if(Session::get('user_language')) {
            $lang = Session::get('user_language');
        }
        
        if(self::validateLangFile($lang))
        {
            return LANGUAGE_PATH.$lang.".php";
        }
        else {
            //try again by forcing default
            //will throw exception if that wont work
            $lang = DEFAULT_LANGUAGE;
            if(self::validateLangFile($lang))
            {
                return LANGUAGE_PATH.$lang.".php";
            }            
        }
    }

    public static function validate($lang) {
        return self::validateLangFile($lang);
    }

    private static function validateLangFile($lang) {
        //check if file exists
        if(file_exists(LANGUAGE_PATH.$lang.".php")) {
            return true;
        } else {
            //if default throw error
            if($lang == DEFAULT_LANGUAGE) {
                throw new Exception("Language file ".$lang.".php not found.", E_USER_ERROR);
            } else {                
                return false;
            }
        }
    }

}
