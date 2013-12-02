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
        // Create our Application instance (replace this with your appId and secret).
        $facebook = new Facebook(array(
            'appId'  => FACEBOOK_LOGIN_APP_ID,
            'secret' => FACEBOOK_LOGIN_APP_SECRET,
        ));

        $this->view->facebook_login_url = $facebook->getLoginUrl(array(
            'redirect_uri' => URL . 'login/loginWithFacebook'
        ));
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
     * The login action, this is where the user is directed after clicking the facebook-login button
     */
    function loginWithFacebook()
    {
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        // perform the login method, put result (true or false) into $login_successful
        $login_successful = $login_model->loginWithFacebook();
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
     * 1. Shows the register form
     * 2. Shows the register-with-facebook button
     * 3. looks if there's
     */
    function register()
    {
        // Create our Application instance (necessary to request Facebook data)
        $facebook = new Facebook(array(
            'appId'  => FACEBOOK_LOGIN_APP_ID,
            'secret' => FACEBOOK_LOGIN_APP_SECRET,
        ));

        $redirect_url_after_facebook_auth = URL . 'login/registerwithfacebook';
        // hard to explain, read the Facebook PHP SDK for more
        // basically, when the user clicks the Facebook register button, the following arguments will be passed
        // to Facebook: In this case a request for getting the email (not shown by default btw) and the URL
        // when facebook will send the user after he/she has authenticated
        $this->view->facebook_login_url = $facebook->getLoginUrl(array(
            'scope' => 'email',
            'redirect_uri' => $redirect_url_after_facebook_auth
        ));

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
     *
     */
    function registerWithFacebook()
    {
        // TODO: put this into the model

        // instantiate the facebook object
        $facebook = new Facebook(array(
            'appId'  => FACEBOOK_LOGIN_APP_ID,
            'secret' => FACEBOOK_LOGIN_APP_SECRET,
        ));

        // get user id (string)
        $user = $facebook->getUser();

        // if the user object (array?) exists, the user has identified as a real facebook user
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $facebook_user_data = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                // TODO: handle the catch results, when something goes wrong with FB login
                // when facebook goes offline
                error_log($e);
                $user = null;
            }
        }

        // TODO: get rid of deep if-nesting!
        // check if we got the user's data array (=$facebook_user_data)
        if ($facebook_user_data) {
            $login_model = $this->loadModel('Login');
            // checken ob user email hat, ansonsten fehler ausgeben
            if ($login_model->facebookUserHasEmail($facebook_user_data)) {
                // checken ob uid schon vorhanden
                if (!$login_model->facebookUserIdExistsAlreadyInDatabase($facebook_user_data)) {
                    // TODO: implement possibility to let potential Facebook user choose another username ?
                    // TODO: automatically rename username (auto-pattern, like adding numbers)
                    // checken ob username schon vorhanden (ohne punkte)
                    if (!$login_model->facebookUserNameExistsAlreadyInDatabase($facebook_user_data)) {
                        // checken ob email vorhanden
                        if (!$login_model->facebookUserEmailExistsAlreadyInDatabase($facebook_user_data)) {
                            // alle vorraussetzungen erfüllt, user kann angelegt werden
                            if ($login_model->registerNewUserWithFacebook($facebook_user_data)) {
                                $this->view->errors[] = "You have been successfully registered with Facebook.";
                                header('location: ' . URL . 'login/index');
                            } else {
                                $this->view->errors[] = "Unknown error while creating your account :(";
                            }
                        } else {
                            $this->view->errors[] = FEEDBACK_FACEBOOK_EMAIL_ALREADY_EXISTS;
                        }
                    } else {
                        $this->view->errors[] = FEEDBACK_FACEBOOK_USERNAME_ALREADY_EXISTS;
                    }
                } else {
                    // a user with that facebook user id (UID) has already registered here
                    $this->view->errors[] = FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS;
                }
            } else {
                // registration will only work when user agrees to provide email address
                $this->view->errors[] = FEEDBACK_FACEBOOK_EMAIL_NEEDED;
            }
        }

        $this->view->render('login/registerwithfacebook');
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
