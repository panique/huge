<?php

/**
 * This is the "base controller class". All other "real" controllers extend this class.
 * Whenever a controller is created, we also
 * 1. initialize a session
 * 2. check if the user is not logged in anymore (session timeout) but has a cookie
 * 3. create a database connection (that will be passed to all models that need a database connection)
 * 4. create a view object
 */
class Controller
{
    /** @var object Database The database connection */
    private $db;

    function __construct()
    {
        Session::init();

        // user has remember-me-cookie ? then try to login with cookie ("remember me" feature)
        if (!isset($_SESSION['user_logged_in']) && isset($_COOKIE['rememberme'])) {
            header('location: ' . URL . 'login/loginWithCookie');
        }

        // create database connection
        try {
            $this->db = new Database();
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }

        // TODO a base model ?
        // $this->model = new Model();

        // TODO maybe making this static ? View::render("xxx");
        // create a view object (that does nothing, but provides the view render() method)
        $this->view = new View();
    }

    /**
     * loads the model with the given name.
     * @param $name string name of the model
     */
    public function loadModel($name)
    {
        $path = PATH_MODEL . ucfirst($name) . 'Model.php';

        if (file_exists($path)) {
            require PATH_MODEL . ucfirst($name) . 'Model.php';
            // "Model" has capital letter as this is the second part of the class name, like "LoginModel"
            $modelName = $name . 'Model';
            // return the new model object while passing the database connection to the model
            return new $modelName($this->db);
        }
    }
}
