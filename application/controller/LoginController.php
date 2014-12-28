<?php

/**
 * LoginController
 * Controls everything that is authentication-related
 */
class LoginController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class. The parent::__construct thing is necessary to
     * put checkAuthentication in here to make an entire controller only usable for logged-in users (for sure not
     * needed in the LoginController).
     */
    function __construct()
    {
        // TODO maybe put model fetching inside controller-constructor ?
        parent::__construct();
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    function index()
    {
        // if user is logged in redirect to main-page
        if ($this->LoginModel->isUserLoggedIn()) {
            // TODO why exactly to mainpage ?
            header("location: " . URL);
        } else {
            // show the view
            $this->View->render('login/index');
        }
    }

    /**
     * The login action, when you do login/login
     */
    function login()
    {
        // perform the login method, put result (true or false) into $login_successful
        $login_successful = $this->LoginModel->login();

        // check login status: if true, then redirect user to dashboard/index, if false, then to login form again
        if ($login_successful) {
            header('location: ' . URL . 'dashboard/index');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * The logout action
     */
    function logout()
    {
        $this->LoginModel->logout();
        // redirect user to main-page
        header('location: ' . URL);
    }

    /**
     * Login with cookie
     */
    function loginWithCookie()
    {
        // run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
         $login_successful = $this->LoginModel->loginWithCookie();

        // if login successful redirect to dashboard/index ...
        if ($login_successful) {
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
            $this->LoginModel->deleteCookie();
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Show user's profile
     */
    // TODO make this the private profile, not the public one
    // TODO don't work with direct session access here ?
    function showProfile()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->View->render('login/showprofile');
    }

    /**
     * Edit user name (show the view with the form)
     */
    function editUsername()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->View->render('login/editusername');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     */
    function editUsername_action()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::checkAuthentication();
        $this->LoginModel->editUserName();
        $this->View->render('login/editusername');
    }

    /**
     * Edit user email (show the view with the form)
     */
    function editUserEmail()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->View->render('login/edituseremail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     */
    // make this POST
    function editUserEmail_action()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->LoginModel->editUserEmail();
        $this->View->render('login/edituseremail');
    }

    /**
     * Upload avatar
     */
    function uploadAvatar()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->View->render('login/uploadavatar', array(
            'avatar_file_path' => $this->LoginModel->getPublicUserAvatarFilePath()
        ));
    }

    /**
     *
     */
    function uploadAvatar_action()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->LoginModel->createAvatar();
        $this->View->render('login/uploadavatar');
    }

    /**
     *
     */
    function changeAccountType()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->View->render('login/changeaccounttype');
    }

    /**
     *
     */
    function changeAccountType_action()
    {
        // Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
        Auth::checkAuthentication();
        $this->LoginModel->changeAccountType();
        $this->View->render('login/changeaccounttype');
    }

    /**
     * Register page
     * Show the register form, but redirect to main-page is user is already logged-in
     */
    function register()
    {
        if ($this->LoginModel->isUserLoggedIn()) {
            header("location: " . URL);
        } else {
            $this->View->render('login/register');
        }
    }

    /**
     * Register page action (POST-request after form submit)
     */
    function register_action()
    {
        $registration_successful = $this->LoginModel->registerNewUser();

        if ($registration_successful) {
            header('location: ' . URL . 'login/index');
        } else {
            header('location: ' . URL . 'login/register');
        }
    }

    /**
     * Verify user after activation mail link opened
     * @param int $user_id user's id
     * @param string $user_activation_verification_code user's verification token
     */
    function verify($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) && isset($user_activation_verification_code)) {
            $this->LoginModel->verifyNewUser($user_id, $user_activation_verification_code);
            $this->View->render('login/verify');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Show the request-password-reset page
     */
    function requestPasswordReset()
    {
        $this->View->render('login/requestpasswordreset');
    }

    /**
     * The request-password-reset action (POST-request after form submit)
     */
    function requestPasswordReset_action()
    {
        $this->LoginModel->requestPasswordReset();
        $this->View->render('login/requestpasswordreset');
    }

    /**
     * Verify the verification token of that user (to show the user the password editing view or not)
     * @param string $user_name username
     * @param string $verification_code password reset verification token
     */
    function verifyPasswordReset($user_name, $verification_code)
    {
        if ($this->LoginModel->verifyPasswordReset($user_name, $verification_code)) {
            // pass URL-provided variable to view to display them
            $this->View->render('login/changepassword', array(
                'user_name' => $user_name,
                'user_password_reset_hash' => $verification_code
            ));
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Set the new password
     * Please note that this happens while the user is not logged in.
     * The user identifies via the data provided by the password reset link from the email.
     */
    // TODO this action might be irritating, rename it
    function setNewPassword()
    {
        // try the password reset (user identified via hidden form inputs ($user_name, $verification_code)), see
        // verifyPasswordReset() for more
        $this->LoginModel->setNewPassword();

        // regardless of result: go to index page (user will get success/error result via feedback message)
        header('location: ' . URL . 'login/index');
    }

    /**
     * Generate a captcha, write the characters into $_SESSION['captcha'] and returns a real image which will be used
     * like this: <img src="......./login/showCaptcha" />
     * IMPORTANT: As this action is called via <img ...> AFTER the real application has finished executing (!), the
     * SESSION["captcha"] has no content when the application is loaded. The SESSION["captcha"] gets filled at the
     * moment the end-user requests the <img .. >
     * Maybe refactor this sometime.
     */
    function showCaptcha()
    {
        $this->LoginModel->generateAndShowCaptcha();
    }
}
