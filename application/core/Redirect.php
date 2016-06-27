<?php

/**
 * Class Redirect
 *
 * Simple abstraction for redirecting the user to a certain page
 */
class Redirect
{
	/**
	 * To the homepage
	 */
	public static function home()
	{
		header("location: " . Config::get('URL'));
		$data = array('destination' => Config::get('URL'));
		new View()->render('_templates/redirect.php', $data);
		exit();
	}

	/**
	 * To the defined page
	 *
	 * @param $path
	 */
	public static function to($path)
	{
		header("location: " . Config::get('URL') . $path);
		$data = array('destination' => Config::get('URL') . $path);
		new View()->render('_templates/redirect.php', $data);
		exit();
	}
}
