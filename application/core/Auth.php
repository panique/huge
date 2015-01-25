<?php

/**
 * Class Auth
 * Checks if user is logged in, if not then sends the user to "yourdomain.com/login".
 * Auth::checkAuthentication() can be used in the constructor of a controller (to make the
 * entire controller only visible for logged-in users) or inside a controller-method to make only this part of the
 * application available for logged-in users.
 */
class Auth
{
    public static function checkAuthentication()
    {
        // initialize the session (if not initialized yet)
        Session::init();

        // if user is not logged in...
        if (!Session::userIsLoggedIn()) {
            // ... then treat user as "not logged in", destroy session, redirect to login page
            Session::destroy();
            header('location: ' . Config::get('URL') . 'login');
            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            // this is not optimal and will be fixed in future releases
            exit();
        }
    }
}
