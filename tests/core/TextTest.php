<?php

class TextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * When argument is existing key, then existing value should be returned
	 */
	public function testGet()
	{
		$this->assertEquals("The username or password is incorrect. Please try again.", Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
	}

	/**
	 * When argument is null, should return null
	 */
	public function testGetWithNullKey()
	{
		$this->assertEquals(null, Text::get(null));
	}

	/**
	 * When key does not exist in text data file, should return null
	 */
	public function testGetWithNonExistingKey()
	{
		$this->assertEquals(null, Text::get('XXX'));
	}
}
