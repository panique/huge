<?php

/**
 * Class Error
 * This controller simply shows a page that will be displayed when a controller/method is not found. Simple 404.
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
     * This is the error page, usually seen by the user when he/she gets an 404.
     * Also see Application -> __construct for more and look for return404andErrorPage()
     */
    public function index()
    {
        $this->View->render('error/index');
    }
}
