<?php

/**
 * Class Auth
 * Simply checks if user is logged in. In the app, several controllers use Auth::handleLogin() to
 * check if user if user is logged in, useful to show controllers/methods only to logged-in users.
 */
class Auth
{
    public static function handleLogin()
    {
        // initialize the session
        Session::init();

        // if user is still not logged in, then destroy session, handle user as "not logged in" and
        // redirect user to login page
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            header('location: ' . URL . 'login');
            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            exit();
        }
    }
}
