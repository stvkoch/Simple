<?php

require_once 'Config/PHP.php';

require_once 'Request/Base.php';
require_once 'Request/HTTP.php';
require_once 'Request/Router.php';


class RequestTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    	\Simple\Config\PHP::$_path = __DIR__.'/config';

    	$_SERVER['SERVER_NAME'] = 'phpunit.test';
    }

    public function testRequest()
	{

		$_SERVER['REQUEST_URI'] = '/xpto/okidoki';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$this->assertEquals('http://phpunit.test/xpto/okidoki', $request->getURL());
		$this->assertEquals(array('foo'=>1, 'bar'=>2) ,$request->getParams());
	}

	public function testRouter()
	{
		$_SERVER['REQUEST_URI'] = '/xpto/okidoki';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routes');
		$router = new \Simple\Request\Router( $routes );

		$resource = array(
			"module"=>"Frontend",
			"controller"=>"Firstcontroller",
			"action"=>"firstaction",
			"format"=>"html",
			"params"=>array()
		);

		$this->assertEquals($resource, $router->getResourceByURI($request->getURI()));

	}

	public function testResourceJson()
	{
		$_SERVER['REQUEST_URI'] = '/xpto/okidoki.json';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routes');
		$router = new \Simple\Request\Router( $routes );

		$resource = array(
			"module"=>"Frontend",
			"controller"=>"Xpto",
			"action"=>"okidoki",
			"format"=> "json",
			"params"=>array()
		);

		$this->assertEquals($resource, $router->getResourceByURI($request->getURI()));
	}

}