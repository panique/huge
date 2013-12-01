<?php

/**
 * Class Auth
 * Simply checks if user is logged in. In the app, several controllers use Auth::handleLogin() to
 * check if user if user is logged in, useful to show controller/method only to logged-in users.
 */
class Auth
{
    public static function handleLogin()
    {
        Session::init();
        
        // if user is still not logged in, then destroy session and handle user as "not logged in"
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            // route user to login page
            header('location: ' . URL . 'login');
        }
    }
}
