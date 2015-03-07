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

	public function testGetDefaultEnvironment()
	{
		// fake application constants
		putenv('APPLICATION_ENV=development');

		// call for environment should return "development"
		$this->assertEquals('index', Config::get('DEFAULT_ACTION'));
	}

	public function testGetFailingEnvironment()
	{
		// fake application constants
		putenv('APPLICATION_ENV=foobar');

		// call for environment should return false because config.foobar.php does not exist
		$this->assertEquals(false, Config::get('DEFAULT_ACTION'));
	}
}
