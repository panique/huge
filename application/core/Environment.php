<?php

/**
 * Class Environment
 *
 * Extremely simple way to get the environment, everywhere inside your application.
 * Extend this the way you want.
 */
class Environment
{
	public static function get()
	{
		// if APPLICATION_ENV constant exists (set in Apache configs)
		// then return content of APPLICATION_ENV
		// else return "development"
		return (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : "development");
	}
}
