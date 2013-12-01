<?php

/**
 * Class Dashboard
 * This is a demo controller that simply shows an area that is only visible for the logged in user
 * because of Auth::handleLogin(); in line 19.
 */
class Dashboard extends Controller
{
	function __construct()
    {
        // a little note on that (seen on StackOverflow):
        // "As long as myChild has no constructor, the parent constructor will be called / inherited."
        // This means wenn a class thats extends another class has a __construct, it needs to construct
        // the parent in that constructor, like this:
		parent::__construct();
                
        // this controller should only be visible/usable by logged in users, so we put login-check here
		Auth::handleLogin();
	}

    /**
     * Action method index().
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    function index()
	{
		$this->view->render('dashboard/index');
	}
}
