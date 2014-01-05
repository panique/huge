<?php

/**
 * Class Help
 * The help area
 */
class Help extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * This method controls what happens when you move to /help/index in your app.
     */
    function index()
    {
        $this->view->render('help/index');
    }
}
