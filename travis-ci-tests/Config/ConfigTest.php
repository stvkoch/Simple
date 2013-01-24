<?php

require_once 'Config/Config.php';

class ConfigTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
        \Simple\Config::$_path = __DIR__.'/config';
    }

	public function testProjectname()
	{
		$this->assertEquals('Simple', \Simple\Config::get('global', 'projectName'));
	}

	public function testDI()
	{
		$this->assertEquals('yourusername', \Simple\Config::get('global', 'handlerPersistencePlace')->username);
		$this->assertEquals('yourpassword', \Simple\Config::get('global', 'handlerPersistencePlace')->password);
	}
}