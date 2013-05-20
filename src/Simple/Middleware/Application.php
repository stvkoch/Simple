<?php
namespace Simple\Middleware;


class Application extends \Simple\Middleware\Base
{

	public function dispatch()
	{
		if( !isset($this->_resource['routesConfigFileName']) )
			throw new Exception("@routesConfigFileName property is not defined in middleware definitions!", 1);

		$routes = \Simple\Config\PHP::getScope($this->_resource['routesConfigFileName']);
		$router = new \Simple\Request\Router($routes );
		$resourceFromRoutes = $router->getResourceByRequest($this->_app->request);
		$this->_app->runResources($resourceFromRoutes);
	}

}