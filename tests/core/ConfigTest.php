<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{
	/*
	 * Create fake values, necessary to run the tests
	 */
	public function setUp()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['SCRIPT_NAME'] = 'index.php';
		Config::$config = null;
	}

	/**
	 * Reset everything
	 */
	public function tearDown()
	{
		putenv('APPLICATION_ENV=');
		Config::$config = null;
	}

    /**
     * Checks if the correct config file for an EXISTING environment / config is called.
     */
	public function testGetDefaultEnvironment()
	{
        // manually set environment to "development"
		putenv('APPLICATION_ENV=development');

		// now get the default action to see if the correct config file (for development) is called
		$this->assertEquals('index', Config::get('DEFAULT_ACTION'));
	}

	public function testGetFailingEnvironment()
	{
        // manually set environment to "foobar" (and non-existing environment)
		putenv('APPLICATION_ENV=foobar');

		// call for environment should return false because config.foobar.php does not exist
		$this->assertEquals(false, Config::get('DEFAULT_ACTION'));
	}
}
