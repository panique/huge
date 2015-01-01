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
        parent::__construct();
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    function index()
    {
        // if user is logged in redirect to main-page, if not show the view
        if ($this->LoginModel->isUserLoggedIn()) {
            header("location: " . URL);
        } else {
            $this->View->render('login/index');
        }
    }

    /**
     * The login action, when you do login/login
     */
    function login()
    {
        // perform the login method, put result (true or false) into $login_successful
        $login_successful = $this->LoginModel->login(
            Request::post('user_name'), Request::post('user_password'), Request::post('set_remember_me_cookie')
        );

        // check login status: if true, then redirect user to dashboard/index, if false, then to login form again
        if ($login_successful) {
            header('location: ' . URL . 'login/showProfile');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * The logout action
     * Perform logout, redirect user to main-page
     */
    function logout()
    {
        $this->LoginModel->logout();
        header('location: ' . URL);
    }

    /**
     * Login with cookie
     */
    function loginWithCookie()
    {
        // run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
         $login_successful = $this->LoginModel->loginWithCookie(Request::cookie('remember_me'));

        // if login successful, redirect to dashboard/index ...
        if ($login_successful) {
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
            $this->LoginModel->deleteCookie();
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Show user's PRIVATE profile
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    function showProfile()
    {
        Auth::checkAuthentication();
        $this->View->render('login/showProfile', array(
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
    function editUsername()
    {
        Auth::checkAuthentication();
        $this->View->render('login/editUsername');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     * Auth::checkAuthentication() makes sure that only logged in users can use this action
     */
    function editUsername_action()
    {
        Auth::checkAuthentication();
        $this->LoginModel->editUserName();
        header('location: ' . URL . 'login/index');
    }

    /**
     * Show edit-my-user-email page
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    function editUserEmail()
    {
        Auth::checkAuthentication();
        $this->View->render('login/editUserEmail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    // make this POST
    function editUserEmail_action()
    {
        Auth::checkAuthentication();
        $this->LoginModel->editUserEmail();
        $this->View->render('login/editUserEmail');
    }

    /**
     * Upload avatar
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    function uploadAvatar()
    {
        Auth::checkAuthentication();
        $this->View->render('login/uploadAvatar', array(
            'avatar_file_path' => $this->LoginModel->getPublicUserAvatarFilePath()
        ));
    }

    /**
     * Perform the upload of the avatar
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     * POST-request
     */
    function uploadAvatar_action()
    {
        Auth::checkAuthentication();
        $this->LoginModel->createAvatar();
        $this->View->render('login/uploadAvatar');
    }

    /**
     * Show the change-account-type page
     * Auth::checkAuthentication() makes sure that only logged in users can use this action and see this page
     */
    function changeAccountType()
    {
        Auth::checkAuthentication();
        $this->View->render('login/changeAccountType');
    }

    /**
     * Perform the account-type changing
     * Auth::checkAuthentication() makes sure that only logged in users can use this action
     * POST-request
     */
    function changeAccountType_action()
    {
        Auth::checkAuthentication();
        $this->LoginModel->changeAccountType();
        $this->View->render('login/changeAccountType');
    }

    /**
     * Register page
     * Show the register form, but redirect to main-page if user is already logged-in
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
     * Register page action
     * POST-request after form submit
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
        $this->View->render('login/requestPasswordReset');
    }

    /**
     * The request-password-reset action
     * POST-request after form submit
     */
    // TODO maybe do this RESTful(ler) :)
    function requestPasswordReset_action()
    {
        $this->LoginModel->requestPasswordReset();
        $this->View->render('login/requestPasswordReset');
    }

    /**
     * Verify the verification token of that user (to show the user the password editing view or not)
     * @param string $user_name username
     * @param string $verification_code password reset verification token
     */
    function verifyPasswordReset($user_name, $verification_code)
    {
        // check if this the provided verification code fits the user's verification code
        if ($this->LoginModel->verifyPasswordReset($user_name, $verification_code)) {
            // pass URL-provided variable to view to display them
            $this->View->render('login/changePassword', array(
                'user_name' => $user_name,
                'user_password_reset_hash' => $verification_code
            ));
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Set the new password
     * Please note that this happens while the user is not logged in. The user identifies via the data provided by the
     * password reset link from the email, automatically filled into the <form> fields. See verifyPasswordReset()
     * for more. Then (regardless of result) route user to index page (user will get success/error via feedback message)
     * POST request !
     */
    function setNewPassword()
    {
        $this->LoginModel->setNewPassword();
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
