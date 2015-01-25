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
	}

	/**
	 * To the defined page
	 *
	 * @param $path
	 */
	public static function to($path)
	{
		header("location: " . Config::get('URL') . $path);
	}
}