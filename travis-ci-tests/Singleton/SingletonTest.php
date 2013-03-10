<?php

require_once 'Config/PHP.php';

require_once 'Singleton/Base.php';
require_once 'Singleton/Registry.php';
require_once 'Request/Router.php';

require_once 'Lib/Foo.php';


class SingletonTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    	\Simple\Config\PHP::$_path = __DIR__.'/config';

    }
    public function testSingleton()
    {
    	\Simple\Singleton\Registry::instanciate( new \Lib\Foo('hello') );

    	$this->assertEquals( \Lib\Foo::getInstance()->h, 'hello' );

    }
}