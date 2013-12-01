<?php

/**
 * Login Controller
 * Controls the login processes
 */
class Login extends Controller
{
    /**
     * Constructor
     */
    function __construct()
    {
        // a little note on that (seen on StackOverflow):
        // "As long as myChild has no constructor, the parent constructor will be called / inherited."
        // This means wenn a class thats extends another class has a __construct, it needs to construct
        // the parent in that constructor, like this:          
        parent::__construct();
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    function index()
    {
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
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $login_model->errors;

        // check login status
        if ($login_successful) {
            // if YES, then move user to dashboard/index
            // please note: this is a browser-redirection, not a rendered view
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if NO, then show the login/index (login form) again
            $this->view->render('login/index');
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
    function loginwithcookie()
    {
        // run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        $login_successful = $login_model->loginWithCookie();

        if ($login_successful) {
            $location = $login_model->getCookieUrl();
            header('location: ' . URL . $location);
        } else {
            // delete the invalid cookie to prevent infinite login loops
            $login_model->deleteCookie();
            // render login/index view
            $this->view->render('login/index');
        }
    }

    /**
     * Show user's profile
     */
    function showprofile()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();        
        $this->view->render('login/showprofile');
    }

    /**
     * Edit user name (show the view with the form)
     */
    function editusername()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();                
        $this->view->render('login/editusername');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     */
    function editusername_action()
    {
        $login_model = $this->loadModel('Login');
        $login_model->editUserName();
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $login_model->errors;
        $this->view->render('login/editusername');
    }

    /**
     * Edit user email (show the view with the form)
     */
    function edituseremail()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();                
        $this->view->render('login/edituseremail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     */
    function edituseremail_action()
    {
        $login_model = $this->loadModel('Login');
        $login_model->editUserEmail();
        $this->view->errors = $login_model->errors;
        $this->view->render('login/edituseremail');
    }

    /**
     * Upload avatar (?)
     * TODO
     */
    function uploadavatar()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $this->view->avatar_file_path = $login_model->getUserAvatarFilePath();
        $this->view->errors = $login_model->errors;
        $this->view->render('login/uploadavatar');        
    }

    /**
     * TODO
     */
    function uploadavatar_action()
    {
        $login_model = $this->loadModel('Login');
        $login_model->createAvatar();
        $this->view->errors = $login_model->errors;
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    function changeaccounttype()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/changeaccounttype');
    }

    /**
     *
     */
    function changeaccounttype_action()
    {
        $login_model = $this->loadModel('Login');
        $login_model->changeAccountType();
        $this->view->errors = $login_model->errors;
        $this->view->render('login/changeaccounttype');          
    }

    /**
     * Register page
     */
    function register()
    {
        $this->view->render('login/register');        
    }

    /**
     * Register page action (after form submit)
     */
    function register_action()
    {
        $login_model = $this->loadModel('Login');
        $registration_successful = $login_model->registerNewUser();
        $this->view->errors = $login_model->errors;
        
        if ($registration_successful == true) {
            $this->view->render('login/index');
        } else {
            $this->view->render('login/register');
        }
    }

    /**
     * Verify user after activation mail sent
     * @param $user_id
     * @param $user_verification_code
     */
    function verify($user_id, $user_verification_code)
    {
        $login_model = $this->loadModel('Login');
        $login_model->verifyNewUser($user_id, $user_verification_code);
        $this->view->errors = $login_model->errors;
        $this->view->render('login/verify');
    }

    /**
     * Request password reset page
     */
    function requestpasswordreset()
    {
        $this->view->render('login/requestpasswordreset');        
    }

    /**
     * Request password reset action (after form submit)
     */
    function requestpasswordreset_action()
    {
        $login_model = $this->loadModel('Login');
        // set token (= a random hash string and a timestamp) into database
        // to see that THIS user really requested a password reset
        if ($login_model->setPasswordResetDatabaseToken() == true) {
            // send a mail to the user, containing a link with that token hash string
            $login_model->sendPasswordResetMail();
        }
        $this->view->errors = $login_model->errors;
        $this->view->render('login/requestpasswordreset');        
    }

    /**
     * @param $user_name
     * @param $verification_code
     */
    function verifypasswordrequest($user_name, $verification_code)
    {
        $login_model = $this->loadModel('Login');

        if ($login_model->verifypasswordrequest($user_name, $verification_code)) {
            $this->view->user_name = $login_model->user_name;
            $this->view->user_password_reset_hash = $login_model->user_password_reset_hash;
            $this->view->errors = $login_model->errors;
            $this->view->render('login/changepassword');
        } else {
            $this->view->errors = $login_model->errors;
            $this->view->render('login/verificationfailed');
        }
    }

    /**
     *
     */
    function setnewpassword()
    {
        $login_model = $this->loadModel('Login');

        if ($login_model->setNewPassword()) {
            $this->view->errors = $login_model->errors;
            $this->view->render('login/index');
        } else {
            $this->view->errors = $login_model->errors;
            $this->view->render('login/changepassword');
        }
    }    
    
    /**
     * special helper method:
     * showCaptcha() returns an image, so we can use it in img tags in the views, like
     * <img src="......./login/showCaptcha" />
     */    
    function showCaptcha()
    {
            $captcha = new Captcha();
            // generate new string with captcha characters and write them into $_SESSION['captcha']
            $captcha->generateCaptcha();
            // render a img showing the characters (=the captcha)
            $captcha->showCaptcha();
    }
}
