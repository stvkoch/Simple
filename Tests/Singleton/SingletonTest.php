<?php

class SingletonTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    	\Simple\Config\PHP::setPath(__DIR__.'/config');

    }
    public function testSingleton()
    {
    	\Simple\Singleton\Registry::instanciate( new \Lib\Foo('hello') );

    	$this->assertEquals( \Lib\Foo::getInstance()->h, 'hello' );

    }
}