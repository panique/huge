<?php

/**
 * UserController
 * Controls everything that is user-related
 */
class UserController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class. The parent::__construct thing is necessary to
     * put checkAuthentication in here to make an entire controller only usable for logged-in users
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show user's PRIVATE profile
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function index()
    {
        Auth::checkAuthentication();
        $this->View->render('user/index', array(
            'user_name' => Session::get('user_name'),
            'user_email' => Session::get('user_email'),
            'user_gravatar_image_url' => Session::get('user_gravatar_image_url'),
            'user_avatar_file' => Session::get('user_avatar_file'),
            'user_account_type' => Session::get('user_account_type')
        ));
    }

    /**
     * Show edit-my-username page
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function editUsername()
    {
        Auth::checkAuthentication();
        $this->View->render('user/editUsername');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     * Auth::checkAuthentication() makes sure that only logged in users can use this action
     */
    public function editUsername_action()
    {
        Auth::checkAuthentication();

        // check if csrf token is valid
        if (!Csrf::isTokenValid()) {
            self::logout();
        }

        UserModel::editUserName(Request::post('user_name'));
        Redirect::to('user/editUsername');
    }

    /**
     * Show edit-my-user-email page
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function editUserEmail()
    {
        Auth::checkAuthentication();
        $this->View->render('user/editUserEmail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    // make this POST
    public function editUserEmail_action()
    {
        Auth::checkAuthentication();
        UserModel::editUserEmail(Request::post('user_email'));
        Redirect::to('user/editUserEmail');
    }

    /**
     * Edit avatar
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function editAvatar()
    {
        Auth::checkAuthentication();
        $this->View->render('user/editAvatar', array(
            'avatar_file_path' => AvatarModel::getPublicUserAvatarFilePathByUserId(Session::get('user_id'))
        ));
    }

    /**
     * Perform the upload of the avatar
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     * POST-request
     */
    public function uploadAvatar_action()
    {
        Auth::checkAuthentication();
        AvatarModel::createAvatar();
        Redirect::to('user/editAvatar');
    }

    /**
     * Delete the current user's avatar
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function deleteAvatar_action()
    {
        Auth::checkAuthentication();
        AvatarModel::deleteAvatar(Session::get("user_id"));
        Redirect::to('user/editAvatar');
    }

    /**
     * Show the change-account-type page
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    public function changeUserRole()
    {
        Auth::checkAuthentication();
        $this->View->render('user/changeUserRole');
    }

    /**
     * Perform the account-type changing
     * Auth::checkAuthentication() makes sure that only logged in users can use this action
     * POST-request
     */
    public function changeUserRole_action()
    {
        Auth::checkAuthentication();

        if (Request::post('user_account_upgrade')) {
            // "2" is quick & dirty account type 2, something like "premium user" maybe. you got the idea :)
            UserRoleModel::changeUserRole(2);
        }

        if (Request::post('user_account_downgrade')) {
            // "1" is quick & dirty account type 1, something like "basic user" maybe.
            UserRoleModel::changeUserRole(1);
        }

        Redirect::to('user/changeUserRole');
    }

    /**
     * Password Change Page
     * Show the password form if user is logged in, otherwise redirect to login page
     */
    public function changePassword()
    {
        Auth::checkAuthentication();
        $this->View->render('user/changePassword');
    }

    /**
     * Password Change Action
     * Submit form, if retured positive redirect to index, otherwise show the changePassword page again
     */
    public function changePassword_action()
    {
        $result = PasswordResetModel::changePassword(
            Session::get('user_name'), Request::post('user_password_current'),
            Request::post('user_password_new'), Request::post('user_password_repeat')
        );

        if($result)
            Redirect::to('user/index');
        else
            Redirect::to('user/changePassword');
    }
}
