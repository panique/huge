<?php

/**
 * Class Index
 * The index controller
 */
class Lang extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
            parent::__construct();
    }
    
    function set($lang)
    {
        $login_model = $this->loadModel('Login');
        $login_model->setDefaultLanguage($lang);
        header('location: ' . URL . 'index');
    }
}
