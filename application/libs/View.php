<?php

/**
 * Class View
 *
 * Provides the methods all views will have
 */
class View extends Smarty
{
    public $lang = "";
    public $css = array();
    public $js = array();
    
    function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->force_compile = SMARTY_FORCE_COMPILE;
        $this->smarty->template_dir = SMARTY_TEMPLATE_DIRECTORY."default/";
        $this->smarty->compile_dir = SMARTY_COMPILE_DIRECTORY;
        $this->smarty->plugins_dir = SMARTY_PLUGINS_DIRECTORY;   
    }
    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param boolean $render_without_header_and_footer Optional: Set this to true if you don't want to include header and footer
     */
    public function render($filename, $render_without_header_and_footer = false)
    {
        
        if(SMARTY_ENABLED)
        {          
            //set language for Smarty right before rendering
            $this->lang = Language::init();
            return self::renderSmarty($filename, $render_without_header_and_footer);
        }
        // page without header and footer, for whatever reason
        if ($render_without_header_and_footer == true) {
            require VIEWS_PATH_OLD . $filename . '.php';
        } else {
            require VIEWS_PATH_OLD . '_templates/header.php';
            require VIEWS_PATH_OLD . $filename . '.php';
            require VIEWS_PATH_OLD . '_templates/footer.php';
        }
    }

    private function renderSmarty($filename, $render_without_header_and_footer = false)
    {
        include_once $this->lang;
        $array = array();
        
        $this->smarty->assign("lang", $lang);
        $this->loadCommonHeaderFiles();
        $this->loadFeedbackMessages();
        $this->smarty->assign("js", $this->js);
        $this->smarty->assign("css", $this->css);
        $this->smarty->assign("feedback", $this->smarty->fetch("_templates/feedback.tpl"));
        //$this->smarty->assign("feedback", $this->smarty->fetch("_templates/feedback.tpl"));
          
        // page without header and footer, for whatever reason
        if ($render_without_header_and_footer == true) {
            array_push($array, $this->smarty->fetch($filename.".tpl"));
        } else {
            array_push($array, $this->smarty->fetch("_templates/header.tpl"));
            array_push($array, $this->smarty->fetch($filename.".tpl"));
            array_push($array, $this->smarty->fetch("_templates/footer.tpl"));
        }      
        $this->smarty->assign("displayArray", $array);
        $this->smarty->display("./display.tpl");
    }
    /**
     * renders the feedback messages into the view
     */
    public function renderFeedbackMessages()
    {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        require VIEWS_PATH_OLD . '_templates/feedback.php';

        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    private function loadFeedbackMessages()
    {
        $this->smarty->assign("feedbackNegative", Session::get('feedback_negative'));
        $this->smarty->assign("feedbackPositive", Session::get('feedback_positive'));        

        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    private function loadCommonHeaderFiles()
    {
        $jquery = "jquery.js";
        if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT']))
        {
            $jquery = "jquery_ie_old.js";
        }        
        
        array_unshift($this->js, $jquery);
        array_unshift($this->css, "reset.css");
    }
    
    /**
     * Checks if the passed string is the currently active controller.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller
     * @return bool Shows if the controller is used or not
     * /
    private function checkForActiveController($filename, $navigation_controller)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];

        return $active_controller == $navigation_controller;
    }*/

    /**
     * Checks if the passed string is the currently active controller-action (=method).
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_action
     * @return bool Shows if the action/method is used or not
     * /
    private function checkForActiveAction($filename, $navigation_action)
    {
        $split_filename = explode("/", $filename);
        $active_action = $split_filename[1];

        return $active_action == $navigation_action;
    }*/

    /**
     * Checks if the passed string is the currently active controller and controller-action.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller_and_action
     * @return bool
     * /
    private function checkForActiveControllerAndAction($filename, $navigation_controller_and_action)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        $active_action = $split_filename[1];

        $split_filename = explode("/", $navigation_controller_and_action);
        $navigation_controller = $split_filename[0];
        $navigation_action = $split_filename[1];

        return $active_controller == $navigation_controller && $active_action == $navigation_action;
    }*/
}
