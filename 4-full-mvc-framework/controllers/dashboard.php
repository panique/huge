<?php

class Dashboard extends Controller {
    
	function __construct() {
            
                // a little note on that (seen on StackOverflow):
                // "As long as myChild has no constructor, the parent constructor will be called / inherited."
                // This means wenn a class thats extends another class has a __construct, it needs to construct
                // the parent in that constructor, like this:            
		parent::__construct();
                
                // this controller should only be visible/usable by logged in users, so we put login-check here
		Auth::handleLogin();
                
                // TODO: js
		//$this->view->js = array('dashboard/js/default.js');
	}
	
	function index() 
	{
		//$this->view->user_name = Session::get('user_name');
		$this->view->render('dashboard/index');
	}
        
	

        
}