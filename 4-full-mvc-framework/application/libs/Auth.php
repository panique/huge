<?php

class Auth {

    public static function handleLogin() {
        
        Session::init();
        
        // if user is still not logged in, then destroy session and handle user as "not logged in"
        if (!isset($_SESSION['user_logged_in'])) {
            
            Session::destroy();
            // route user to login page
            header('location: ' . URL . 'login');            
            
        }
    }
}
