<?php

namespace Simple\Middleware;


class Router extends \Simple\Middleware\Base
{
	protected $_resources;

	public function parse()
	{

		if( !isset($this->resource['routesFileNameConfig']) )
		{
			throw new \Exception("@routesFileNameConfig property is not defined in middleware definitions!", 1);
		}

		$routes = \Simple\Config\PHP::getScope($this->resource['routesFileNameConfig']);
		$router = new \Simple\Request\Router($routes );
		$this->_resources = $router->getResourcesByRequest($this->backbone->getResourceById('simple.request')->getRequest());

		return true;
	}


    /**
     * Gets the value of _resources.
     *
     * @return mixed
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * 
     */
    public function getHash() {
    	return md5(json_encode($this->_resources));
    }
}