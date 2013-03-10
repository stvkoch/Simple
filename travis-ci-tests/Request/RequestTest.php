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

		$_SERVER['REQUEST_URI'] = '/xpto/actionName';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$this->assertEquals('http://phpunit.test/xpto/actionName', $request->getURL());
		$this->assertEquals(array('foo'=>1, 'bar'=>2) ,$request->getParams());
	}

	public function testRouter()
	{
		$_SERVER['REQUEST_URI'] = '/moduleName/controllerName/actionName3';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routes');
		$router = new \Simple\Request\Router( $routes );

		$resource = array(
			"module"=>"moduleName",
			"controller"=>"controllerName",
			"action"=>"firstaction",
			"format"=>"html",
			"params"=>array()
		);
		$resourceFromRoute = $router->getResourceByURI($request->getURI());
		//var_dump($request->getURI(),$resource, $resourceFromRoute);
		$this->assertEquals($resource, $resourceFromRoute);

	}

	public function testRouter2()
	{
		$_SERVER['REQUEST_URI'] = '/controllerName/show';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routes');
		$router = new \Simple\Request\Router( $routes );

		$resource = array(
			"module"=>"Frontend",
			"controller"=>"\Vendor\Namespace\controllerName::show",
			"action"=>"show",
			"format"=>"html",
			"params"=>array()
		);
		$resourceFromRoute = $router->getResourceByURI($request->getURI());
		$this->assertEquals($resource, $resourceFromRoute);

	}

	public function testResourceJson()
	{
		$_SERVER['REQUEST_URI'] = '/xpto/actionName.json';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routes');
		$router = new \Simple\Request\Router( $routes );

		$resource = array(
			"module"=>"Frontend",
			"controller"=>"index",
			"action"=>"index",
			"format"=> "json",
			"params"=>array()
		);

		$resourceFromRoute = $router->getResourceByURI($request->getURI());

		$this->assertEquals($resource, $resourceFromRoute);
	}

}