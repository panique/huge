<?php

class Help extends Controller {

	function __construct() {
            
		parent::__construct();
	}
	
	function index() {
            
		$this->view->render('help/index');	
	}

}