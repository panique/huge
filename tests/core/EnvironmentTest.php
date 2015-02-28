<?php

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
	public function testGetDefault()
	{
		// call for environment should return "development"
		$this->assertEquals('development', Environment::get());
	}

	public function testGetDevelopment()
	{
		putenv('APPLICATION_ENV=development');
		// call for environment should return "development"
		$this->assertEquals('development', Environment::get());
	}

	public function testGetProduction()
	{
		putenv('APPLICATION_ENV=production');
		$this->assertEquals('production', Environment::get());
	}
}
