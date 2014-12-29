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
    private $database;

    function __construct()
    {
        Session::init();

        // user is not logged in but has remember-me-cookie ? then try to login with cookie ("remember me" feature)
        // TODO encapsulate COOKIE super-global
        // TODO rename to remember_me to fit any IDE's spell check
        if (!Session::userIsLoggedIn() AND isset($_COOKIE['rememberme'])) {
            header('location: ' . URL . 'login/loginWithCookie');
        }

        // create database connection
        try {
            $this->database = new Database();
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }

        // TODO it's not a good idea to load ALL models by default, or ? let's discuss this.
        // TODO check performance vs. usability when pre-loading ALL models
        // TODO replace this with some kind of "model"-autoloader
        // TODO as "model" is just a layer in the application there cannot be multiple "models", so maybe rename this ?
        $this->NoteModel = new NoteModel($this->database);
        $this->LoginModel = new LoginModel($this->database);
        $this->ProfileModel = new ProfileModel($this->database);

        // create a view object (that does nothing, but provides the view render() method)
        $this->View = new View();
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
            return new $modelName($this->database);
        }
    }
}
