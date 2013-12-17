<?php

/**
 * Class View
 */
class View
{
    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param boolean $special_page Optional: Set this to true if you don't want to include header and footer
     */
    public function render($filename, $special_page = false)
    {
        // special page = page without header and footer, for whatever reason
        if ($special_page == true) {
            require VIEWS_PATH . $filename . '.php';
        } else {
            require VIEWS_PATH . '_templates/header.php';
            require VIEWS_PATH . $filename . '.php';
            require VIEWS_PATH . '_templates/footer.php';
        }
    }

    public function renderFeedbackMessages()
    {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        require VIEWS_PATH . '_templates/feedback.php';

        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    
    /**
     * useful for handling the navigation's active/non-active link
     * (eventually this needs work !)
     * @param $filename
     * @param $navigation_controller
     * @return bool Shows if the controller is used or not
     */
    private function checkForActiveController($filename, $navigation_controller)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        
        if ($active_controller == $navigation_controller) {
            return true;
        }
        // default return if not true
        return false;
    }

    /**
     * @param $filename
     * @param $navigation_action
     * @return bool Shows if the action/method is used or not
     */
    private function checkForActiveAction($filename, $navigation_action)
    {
        $split_filename = explode("/", $filename);
        $active_action = $split_filename[1];
        
        if ($active_action == $navigation_action) {
            return true;
        }
        // default return of not true
        return false;
    }

    /**
     * @param $filename
     * @param $navigation_controller_and_action
     * @return bool
     */
    private function checkForActiveControllerAndAction($filename, $navigation_controller_and_action)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        $active_action = $split_filename[1];
        
        $split_filename = explode("/", $navigation_controller_and_action);
        $navigation_controller = $split_filename[0];
        $navigation_action = $split_filename[1];
        
        if ($active_controller == $navigation_controller AND $active_action == $navigation_action) {
            return true;
        }
        // default return of not true
        return false;
    }
}
