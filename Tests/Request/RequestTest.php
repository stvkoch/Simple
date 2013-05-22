<?php


class RequestTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    	\Simple\Config\PHP::setPath(__DIR__.'/config');

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

    public function testParams()
	{

		$_SERVER['REQUEST_URI'] = '/xpto/actionName/param1/param2';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routes');

		$router = new \Simple\Request\Router( $routes );
		$resourcesFromRoute = $router->getResourcesByRequest($request);

		$this->assertEquals('http://phpunit.test/xpto/actionName/param1/param2', $request->getURL());
		$this->assertEquals(array('param1', 'param2', 'foo'=>1, 'bar'=>2) ,$request->getParams());
	}


}