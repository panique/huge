<?php

/**
 * Class Overview
 * This controller shows date of one or all user(s)
 */
class Overview extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        // get all users that exist and put the result into $this->view->users
        // in the view this is available in $this->users
        $overview_model = $this->loadModel('Overview');
        $this->view->users = $overview_model->getAllUsersProfiles();
        $this->view->render('overview/index');
    }

    function showuserprofile($user_id)
    {
        $overview_model = $this->loadModel('Overview');
        $this->view->user = $overview_model->getUserProfile($user_id);
        $this->view->render('overview/showuserprofile');
    }
}
