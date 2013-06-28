<?php

class Auth {

    public static function handleLogin() {
        
        Session::init();
        
        if ($_SESSION['user_logged_in'] == false) {
            
            Session::destroy();
        }
    }

}