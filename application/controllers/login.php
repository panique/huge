<?php

/**
 * Login Controller
 * Controls the login processes
 */

class Login extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    function index()
    {
        // create a login model to perform the getFacebookLoginUrl() method
        $login_model = $this->loadModel('Login');

        // if we use Facebook: this is necessary as we need the facebook_login_url in the login form (in the view)
        if (FACEBOOK_LOGIN == true) {
            $this->view->facebook_login_url = $login_model->getFacebookLoginUrl();
        }

        // show the view
        $this->view->render('login/index');
    }

    /**
     * The login action, when you do login/login
     */
    function login()
    {
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        // perform the login method, put result (true or false) into $login_successful
        $login_successful = $login_model->login();

        // check login status
        if ($login_successful) {
            // if YES, then move user to dashboard/index (btw this is a browser-redirection, not a rendered view!)
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if NO, then move user to login/index (login form) again
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * The login action, this is where the user is directed after being checked by the Facebook server by
     * clicking the facebook-login button
     */
    function loginWithFacebook()
    {
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        $login_successful = $login_model->loginWithFacebook();

        // check login status
        if ($login_successful) {
            // if YES, then move user to dashboard/index (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * The logout action, login/logout
     */
    function logout()
    {
        $login_model = $this->loadModel('Login');
        $login_model->logout();
        // redirect user to base URL
        header('location: ' . URL);
    }

    /**
     * Login with cookie
     */
    function loginWithCookie()
    {
        // run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        $login_successful = $login_model->loginWithCookie();

        if ($login_successful) {
            header('location: ' . URL . 'dashboard/index');
        } else {
            // delete the invalid cookie to prevent infinite login loops
            $login_model->deleteCookie();
            // if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Show user's profile
     */
    function showProfile()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/showprofile');
    }

    /**
     * Edit user name (show the view with the form)
     */
    function editUsername()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/editusername');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     */
    function editUsername_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->editUserName();
        $this->view->render('login/editusername');
    }

    /**
     * Edit user email (show the view with the form)
     */
    function editUserEmail()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/edituseremail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     */
    function editUserEmail_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->editUserEmail();
        $this->view->render('login/edituseremail');
    }

    /**
     * Upload avatar
     */
    function uploadAvatar()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $this->view->avatar_file_path = $login_model->getUserAvatarFilePath();
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    function uploadAvatar_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->createAvatar();
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    function changeAccountType()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/changeaccounttype');
    }

    /**
     *
     */
    function changeAccountType_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->changeAccountType();
        $this->view->render('login/changeaccounttype');
    }

    /**
     * Register page
     * Show the register form (with the register-with-facebook button). We need the facebook-register-URL for that.
     */
    function register()
    {
        $login_model = $this->loadModel('Login');

        // if we use Facebook: this is necessary as we need the facebook_register_url in the login form (in the view)
        if (FACEBOOK_LOGIN == true) {
            $this->view->facebook_register_url = $login_model->getFacebookRegisterUrl();
        }

        $this->view->render('login/register');
    }

    /**
     * Register page action (after form submit)
     */
    function register_action()
    {
        $login_model = $this->loadModel('Login');
        $registration_successful = $login_model->registerNewUser();

        if ($registration_successful == true) {
            header('location: ' . URL . 'login/index');
        } else {
            header('location: ' . URL . 'login/register');
        }
    }

    /**
     * Register a user via Facebook-authentication
     */
    function registerWithFacebook()
    {
        $login_model = $this->loadModel('Login');
        // perform the register method, put result (true or false) into $registration_successful
        $registration_successful = $login_model->registerWithFacebook();

        // check registration status
        if ($registration_successful) {
            // if YES, then move user to login/index (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        } else {
            // if NO, then move user to login/register (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/register');
        }
    }

    /**
     * Verify user after activation mail link opened
     * @param int $user_id user's id
     * @param string $user_activation_verification_code sser's verification token
     */
    function verify($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) && isset($user_activation_verification_code)) {
            $login_model = $this->loadModel('Login');
            $login_model->verifyNewUser($user_id, $user_activation_verification_code);
            $this->view->render('login/verify');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Request password reset page
     */
    function requestPasswordReset()
    {
        $this->view->render('login/requestpasswordreset');
    }

    /**
     * Request password reset action (after form submit)
     */
    function requestPasswordReset_action()
    {
        $login_model = $this->loadModel('Login');
        $login_model->requestPasswordReset();
        $this->view->render('login/requestpasswordreset');
    }

    /**
     * Verify the verification token of that user (to show the user the password editing view or not)
     * @param string $user_name username
     * @param string $verification_code password reset verification token
     */
    function verifyPasswordReset($user_name, $verification_code)
    {
        $login_model = $this->loadModel('Login');
        if ($login_model->verifyPasswordReset($user_name, $verification_code)) {
            // get variables for the view
            $this->view->user_name = $user_name;
            $this->view->user_password_reset_hash = $verification_code;
            $this->view->render('login/changepassword');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Set the new password
     * Please note that this happens while the user is not logged in.
     * The user identifies via the data provided by the password reset link from the email.
     */
    function setNewPassword()
    {
        $login_model = $this->loadModel('Login');
        // try the password reset (user identified via hidden form inputs ($user_name, $verification_code)), see
        // verifyPasswordReset() for more
        $login_model->setNewPassword();
        // regardless of result: go to index page (user will get success/error result via feedback message)
        header('location: ' . URL . 'login/index');
    }

    /**
     * Generate a captcha, write the characters into $_SESSION['captcha'] and returns a real image which will be used
     * like this: <img src="......./login/showCaptcha" />
     * IMPORTANT: As this action is called via <img ...> AFTER the real application has finished executing (!), the
     * SESSION["captcha"] has no content when the application is loaded. The SESSION["captcha"] gets filled at the
     * moment the end-user requests the <img .. >
     * If you don't know what this means: Don't worry, simply leave everything like it is ;)
     */
    function showCaptcha()
    {
        $login_model = $this->loadModel('Login');
        $login_model->generateCaptcha();
    }
}
