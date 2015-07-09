<?php

/**
 * Class Error
 * This controller simply contains some methods that can be used to give proper feedback in certain error scenarios,
 * like a proper 404 response with an additional HTML page behind when something does not exist.
 */
class ErrorController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Use this when something is not found. Gives back a proper 404 header response plus a normal page (where you could
     * show a well-designed error message or something more useful for your users).
     * You can see this in action in action in /core/Application.php -> __construct
     */
    public function error404()
    {
        header('HTTP/1.0 404 Not Found', true, 404);
        $this->View->render('error/404');
    }
}
