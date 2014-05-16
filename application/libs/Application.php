<?php

/**
 * Class Application
 * The heart of the app
 */
class Application
{
	/** @var null The controller part of the URL */
	private $url_controller;
	/** @var null The method part (of the above controller) of the URL */
	private $url_action;
	/** @var null Parameter one of the URL */
	private $url_parameter_1;
	/** @var null Parameter two of the URL */
	private $url_parameter_2;
	/** @var null Parameter three of the URL */
	private $url_parameter_3;

	/**
	 * Starts the Application
	 * Takes the parts of the URL and loads the according controller & method and passes the parameter arguments to it
	 * TODO: make the hardcoded locations ("error/index", "index.php", new Index()) dynamic, maybe via config.php
	 */
	public function __construct()
	{
		$this->splitUrl();

		// if url_controller is empty, simply show the main page (index/index)
		if (!$this->url_controller) {
			require CONTROLLERS_PATH . "index.php";
			$controller = new Index();
			$controller->index();
			exit;
		}

		// If url_action is empty, default it to 'index'
		if (!$this->url_action) {
			$this->url_action = "index";
		}

		// Either the controller doesn't exist, or the method doesn't exist inside the controller; We exit to the error page
		if (!file_exists(CONTROLLERS_PATH . $this->url_controller . ".php") or !method_exists($this->url_controller, $this->url_action)) {
			header("location: " . URL . "error/index/");
			exit;
		}

		// We've reached this far, so the file exists and the method does too
		// Load the controller file and create the controller
		// example: if controller would be "car", then this line would translate into: $this->car = new car();
		require CONTROLLERS_PATH . $this->url_controller . ".php";
		$this->url_controller = new $this->url_controller();

		// Call the method and pass the arguments to it
		if (isset($this->url_parameter_3)) {
			// Pass it 3 parameters
			$this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2, $this->url_parameter_3);
		} elseif (isset($this->url_parameter_2)) {
			// Pass it 2 parameters
			$this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2);
		} elseif (isset($this->url_parameter_1)) {
			// Pass it 1 parameter
			$this->url_controller->{$this->url_action}($this->url_parameter_1);
		} else {
			// No parameters were given, so just call the method without any parameters
			$this->url_controller{$this->url_action}();
		}
	}

	/**
	 * Gets and splits the URL
	 */
	private function splitUrl()
	{
		if (isset($_GET['url'])) {

			// split URL
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);

			// Put URL parts into according properties
			// By the way, the syntax here if just a short form of if/else, called "Ternary Operators"
			// http://davidwalsh.name/php-shorthand-if-else-ternary-operators
			$this->url_controller = (isset($url[0]) ? $url[0] : null);
			$this->url_action = (isset($url[1]) ? $url[1] : null);
			$this->url_parameter_1 = (isset($url[2]) ? $url[2] : null);
			$this->url_parameter_2 = (isset($url[3]) ? $url[3] : null);
			$this->url_parameter_3 = (isset($url[4]) ? $url[4] : null);
		}
	}
}