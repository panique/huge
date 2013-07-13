<?php

class Login extends Controller {

    function __construct() {
        
        parent::__construct();
    }

    function index() {
        
        $this->view->render('login/index');
    }

    function login() {
        
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $login_successful = $this->model->login();

        // TODO: find a better solution than always doing this by hand
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $this->model->errors;

        // check login status
        if ($login_successful) {
            
            // if YES, then move user to dashboard/index
            // please note: this is a browser-relocater, not a rendered view
            header('location: ' . URL . 'dashboard/index');
            //$this->view->render('dashboard/index');
            
        } else {
            
            // if NO, then show the login/index (login form) again
            $this->view->render('login/index');
        }
    }
    
    function logout()
    {
            Session::destroy();
            header('location: ' . URL .  'login');
            //exit;
    }    
    
    function editusername() {
        
        $this->view->render('login/editusername');        
        
    }
    
    function editusername_action() {
        
        $this->model->editUserName();
        
        // TODO: find a better solution than always doing this by hand
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $this->model->errors;
        
        $this->view->render('login/editusername');        
        
    }    
    
    function edituseremail() {
        
        $this->view->render('login/edituseremail');
        
    }
    
    function edituseremail_action() {
        
        $this->model->editUserEmail();

        // TODO: find a better solution than always doing this by hand
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $this->model->errors;
        
        $this->view->render('login/edituseremail');        
        
    }  

    // register page
    function register() {    
        
        $this->view->render('login/register');
        
    }
    
    // real registration action
    function register_action() {
        
        $registration_successful = $this->model->registerNewUser();

        // TODO: find a better solution than always doing this by hand
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $this->model->errors;
        
        if ($registration_successful == true) {
            $this->view->render('login/index');
        } else {
            $this->view->render('login/register');
        }
        
        
    }
    
    function verify($user_email, $user_verification_code) {
        
        $this->model->verifyNewUser($user_email, $user_verification_code);

        // TODO: find a better solution than always doing this by hand
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $this->model->errors;
        
        $this->view->render('login/verify');
        
    }
    
    function requestpasswordreset() {
        
        $this->view->render('login/requestpasswordreset');
        
    }
    
    function requestpasswordreset_action() {
        
        //$this->model->requestPasswordReset();
        
        // set token (= a random hash string and a timestamp) into database, to see that THIS user really requested a password reset
        if ($this->model->setPasswordResetDatabaseToken() == true) {
        
            // send a mail to the user, containing a link with that token hash string
            $this->model->sendPasswordResetMail();
            
        }

        // TODO: find a better solution than always doing this by hand
        // put the errors from the login model into the view (so we can display them in the view)
        $this->view->errors = $this->model->errors;
        
        $this->view->render('login/requestpasswordreset');
        
    }  
    
    function verifypasswordrequest($user_name, $verification_code) {
        
        if ($this->model->verifypasswordrequest($user_name, $verification_code)) {
            
            $this->view->user_name = $this->model->user_name;
            $this->view->user_password_reset_hash = $this->model->user_password_reset_hash;
            
            $this->view->errors = $this->model->errors;        
            $this->view->render('login/changepassword');
            
        } else {
            
            $this->view->errors = $this->model->errors;        
            $this->view->render('login/verificationfailed');
        }
        
    }
    
    function setnewpassword() {
        
        if ($this->model->setNewPassword()) {

            $this->view->errors = $this->model->errors;        
            $this->view->render('login/index');                    
            
        } else {

            $this->view->errors = $this->model->errors;        
            $this->view->render('login/changepassword');                                
            
        }
        
    }    
    
    /**
     * special helper method:
     * showCaptcha() returns an image, so we can use it in img tags in the views, like
     * <img src="......./login/showCaptcha" />
     */    
    function showCaptcha() {
        
            $captcha = new Captcha();
            // generate new string with captcha characters and write them into $_SESSION['captcha']
            $captcha->generateCaptcha();
            // render a img showing the characters (=the captcha)
            $captcha->showCaptcha();
    }    

}