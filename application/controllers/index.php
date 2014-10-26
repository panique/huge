<?php

/**
 * Class Index
 * The index controller
 */
class Index extends Controller
{
    /**  
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
            parent::__construct();
    }

    /**
     * Handles what happens when user moves to URL/index/index, which is the same like URL/index or in this
     * case even URL (without any controller/action) as this is the default controller-action when user gives no input.
     */
    function index()
    {
            $this->view->render('index/index');
    }

    /**
     * Handle the change events happening in the language_form provided by the Lang library and appearing in the header.php template view
     * @author Tristan Vanrullen
     */
     
    function language_form_action()
    {
    	if (isset($_GET['language_selector']))
    		Session::set('current_language',$_GET['language_selector']);
    	Lang::initLanguage(true);
    	if (isset($_GET['current_page']))
    	{
    		//instead of rendering the view (and staying at the same address irrelevant to the view, we have to actually redirect to the desired page
    		// route user to login page
    		header('location: ' . URL . $_GET['current_page']);
    	}
    	else $this->view->render('index/index');
    }
}
