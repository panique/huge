<?php

class View {

    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param boolean $special_page Optional: Set this to true if you don't want to include header and footer
     */
    public function render($filename, $special_page = false) {
        
        if ($special_page == true) {
            
            require 'views/' . $filename . '.php';
            
        } else {
            
            require 'views/_templates/header.php';
            require 'views/' . $filename . '.php';
            require 'views/_templates/footer.php';
            
        }
        
    }
    
    /*
     * useful for handling the navigation's active/non-active link
     * ...
     * TODO
     */
    private function checkForActiveController($filename, $navigation_controller) {
        
        $splitted_filename = explode("/", $filename);
        $active_controller = $splitted_filename[0];
        
        if ($active_controller == $navigation_controller) {
            
            return true;
            
        } else {
            
            return false;
        }
        
    }
    
    private function checkForActiveAction($filename, $navigation_action) {
        
        $splitted_filename = explode("/", $filename);
        $active_action = $splitted_filename[1];
        
        if ($active_action == $navigation_action) {
            
            return true;
            
        } else {
            
            return false;
        }
        
    }    
    
    private function checkForActiveControllerAndAction($filename, $navigation_controller_and_action) {
        
        $splitted_filename = explode("/", $filename);
        $active_controller = $splitted_filename[0];
        $active_action = $splitted_filename[1];
        
        $splitted_filename = explode("/", $navigation_controller_and_action);
        $navigation_controller = $splitted_filename[0];
        $navigation_action = $splitted_filename[1];        
        
        if ($active_controller == $navigation_controller AND $active_action == $navigation_action) {
            
            return true;
            
        } else {
            
            return false;
        }
        
    }    

}