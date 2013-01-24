<?php

require_once 'Config/Config.php';

class ConfigTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
        \Simple\Config\Config::$_path = __DIR__.'/config';
    }

	public function testProjectname()
	{
		$this->assertEquals('Simple', \Simple\Config\Config::get('global', 'projectName'));
	}

	public function testDI()
	{
		$this->assertEquals('yourusername', \Simple\Config\Config::get('global', 'handlerPersistencePlace')->username);
		$this->assertEquals('yourpassword', \Simple\Config\Config::get('global', 'handlerPersistencePlace')->password);
	}

	public function testSingleston1(){
		$this->assertEquals(3, \Simple\Config\Config::get('global', 'handlerPersistencePlace')->count);
		$this->assertEquals(1, \Simple\Config\Config::get('global', 'handlerPersistencePlace')->singleton);
	}

	public function testSingleston2(){
		$this->assertEquals(5, \Simple\Config\Config::get('global', 'handlerPersistencePlace')->count);
		$this->assertEquals(1, \Simple\Config\Config::get('global', 'handlerPersistencePlace')->singleton);
	}

}