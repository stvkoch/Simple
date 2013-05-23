<?php

class RouterTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    	\Simple\Config\PHP::setPath(__DIR__.'/config');

    	$_SERVER['SERVER_NAME'] = 'phpunit.test';
    }

    public function testDisableWarning() {
    	$this->assertEquals(true, true);
    }

	public function testMiddlewares()
	{
		$_SERVER['REQUEST_URI'] = '/admin/controller/function';
		$_SERVER['REQUEST_METHOD'] = 'TEST';
		$_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
		$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		$routes = \Simple\Config\PHP::getScope('routesesource');

		$router = new \Simple\Request\Router( $routes );

		$resources = array(

			array(
				'namespace' => '\Simple\Middleware\View',
				'class'=>'Cache',
				'function'=>'open',
				'id'=>'Simple.middleware.view.cache.open'
			),

  			array(
				'namespace' => '\Frontend\Middleware',
				'class'=>'Flash',
				'function'=>'openFlash',
				'id'=>'front.mid.flash.open'
			),

    	);

		$resourcesFromRoute = $router->getResourcesByRequest($request);

		$this->assertEmpty(array_diff_assoc($resources, $resourcesFromRoute));

	}
/*
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
			"controller"=>"controllerName",
			"action"=>"show",
			"format"=>"html",
			"params"=>array()
		);
		$resourceFromRoute = $router->getResourceByRequest($request);
		$this->assertEquals($resource, $resourceFromRoute);

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
			"controller"=>"xpto",
			"action"=>"okidoki",
			"format"=> "json",
			"params"=>array()
		);

		$resourceFromRoute = $router->getResourceByRequest($request);

		$this->assertEquals($resource, $resourceFromRoute);
	}
*/
}