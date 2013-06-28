<?php

class View {

    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $name Path of the to be rendered view, usually folder/file.php
     * @param boolean $special_page Optional: Set this to true if you don't want to include header and footer
     */
    public function render($name, $special_page = false) {
        
        if ($special_page == true) {
            
            require 'views/' . $name . '.php';
            
        } else {
            
            require 'views/header.php';
            require 'views/' . $name . '.php';
            require 'views/footer.php';
            
        }
        
    }

}