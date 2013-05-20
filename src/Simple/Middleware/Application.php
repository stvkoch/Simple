<?php
namespace Simple\Middleware;


class Application extends \Simple\Middleware\Base
{

	public function dispatch()
	{
		if( !isset($this->resource['routesFileNameConfig']) )
		{
			throw new \Exception("@routesFileNameConfig property is not defined in middleware definitions!", 1);
		}

		$routes = \Simple\Config\PHP::getScope($this->resource['routesFileNameConfig']);
		$router = new \Simple\Request\Router($routes );
		$resourceFromRoutes = $router->getResourcesByRequest($this->app->request);

		$this->app->runResources($resourceFromRoutes);
	}

}