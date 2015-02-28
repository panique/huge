<?php

class TextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * When argument is existing key, then existing value should be returned
	 */
	public function testGet()
	{
		$this->assertEquals('Password was wrong.', Text::get('FEEDBACK_PASSWORD_WRONG'));
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
