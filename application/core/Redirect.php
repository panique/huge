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
		return print "<html><script>document.location.href='" . Config::get('URL') . "';</script></html>";
	}

	/**
	 * To the defined page
	 *
	 * @param $path
	 */
	public static function to($path)
	{
		header("location: " . Config::get('URL') . $path);
		return print "<html><script>document.location.href='" . Config::get('URL') . $path . "';</script></html>";
	}
}
