<?php

/**
 * This is the "base controller class". All other "real" controllers extend this class. That means, that for example
 * ....
 * ....
 * ....
 */
class Controller {

    function __construct() {

        // everytime a controller is created, start a session
        // TODO: this is a singleton. should this be handled in another way ?
        Session::init();

        // everytime a controller is created, create a view object (that does nothing, but provides the render() method)
        $this->view = new View();
    }

    /**
     * loads the model with the given name.
     * loadModel("test") would include models/test_model.php and create the object $this->model in the controller
     * 
     * @param string $name The name of the model
     */
    public function loadModel($name) {

        $path = 'models/' . $name . '_model.php';

        if (file_exists($path)) {
            require 'models/' . $name . '_model.php';

            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
    }

}