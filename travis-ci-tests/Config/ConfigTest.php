<?php

require_once 'Config/PHP.php';

class ConfigTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
        \Simple\Config\PHP::$_path = __DIR__.'/config';
    }

	public function testProjectname()
	{
		$this->assertEquals('Simple', \Simple\Config\PHP::get('global', 'projectName'));
	}

	public function testDI()
	{
		$this->assertEquals('yourusername', \Simple\Config\PHP::get('global', 'handlerPersistencePlace')->username);
		$this->assertEquals('yourpassword', \Simple\Config\PHP::get('global', 'handlerPersistencePlace')->password);
	}

	public function testSingleston1(){
		$this->assertEquals(3, \Simple\Config\PHP::get('global', 'handlerPersistencePlace')->count);
		$this->assertEquals(1, \Simple\Config\PHP::get('global', 'handlerPersistencePlace')->singleton);
	}

	public function testSingleston2(){
		$this->assertEquals(5, \Simple\Config\PHP::get('global', 'handlerPersistencePlace')->count);
		$this->assertEquals(1, \Simple\Config\PHP::get('global', 'handlerPersistencePlace')->singleton);
	}

}