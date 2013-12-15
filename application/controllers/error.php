<?php

/**
 * Class Error
 * This controller simply shows a page that will be displayed when a controller/method is not found.
 * Simple 404 handling.
 */
class Error extends Controller
{
	function __construct()
    {
		parent::__construct();
	}
	
	function index()
    {
		$this->view->render('error/index');
	}
}
