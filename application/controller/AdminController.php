<?php

class AdminController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method controls what happens when you move to /overview/index in your app.
     * Shows a list of all users.
     */
    public function index()
    {
	    If(Session::get('user_account_type') != 2) Redirect::home();
	    $this->View->render('admin/index', array(
			    'users' => UserModel::getPublicProfilesOfAllUsers())
	    );
    }

	public function actionAccountSettings()
	{
		If(Session::get('user_account_type') != 2) Redirect::home();
		AdminModel::setAccountSusspensionAndDeletetionStatus(
			Request::post('suspension'), Request::post('softDelete'), Request::post('user_id')
		);
		redirect::to("admin");
	}

}
