<?php

/**
 * Cross Site Request Forgery Class
 *
 */

/**
 * Instructions:
 *
 * At your form, before the submit button put:
 * <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
 *
 * This validation needed in the controller action method to validate CSRF token submitted with the form:
 *
 * if (!Csrf::isTokenValid()) {
 *     LoginModel::logout();
 *     Redirect::home();
 *     exit();
 * }
 *
 * To get simpler code it might be better to put the logout, redirect, exit into an own (static) method.
 */
class Csrf {

    /**
     * get CSRF token and generate a new one if expired
     *
     * @access public
     * @static static method
     * @return string
     */
    public static function makeToken()
    {
        // token is valid for 1 day
        $max_time    = 60 * 60 * 24;
        $stored_time = Session::get('csrf_token_time');
        $csrf_token  = Session::get('csrf_token');

        if($max_time + $stored_time <= time() || empty($csrf_token)){
            Session::set('csrf_token', md5(uniqid(rand(), true)));
            Session::set('csrf_token_time', time());
        }

        return Session::get('csrf_token');
    }

    /**
     * checks if CSRF token in session is same as in the form submitted
     *
     * @access public
     * @static static method
     * @return bool
     */
    public static function isTokenValid()
    {
        $token = Request::post('csrf_token');
        return $token === Session::get('csrf_token') && !empty($token);
    }
}
