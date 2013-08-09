<?php

class Overview extends Controller {

    function __construct() {
        
        parent::__construct();
    }

    function index() {
        
        // get all users that exist and put the result into $this->view->users
        // in the view this is availabile in $this->users
        $this->view->users = $this->model->getAllUsersProfiles();
        $this->view->render('overview/index');
    }

    function showuserprofile($user_id) {

        $this->view->user = $this->model->getUserProfile($user_id);
        $this->view->render('overview/showuserprofile');
        
    }
    
}