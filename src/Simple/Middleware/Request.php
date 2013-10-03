<?php

namespace Simple\Middleware;


class Request extends \Simple\Middleware\Base
{
	protected $_request=null;
    protected $_resources=null;

	public function parse()
	{

		$this->parseRequest();

		$this->parseRouter();

        $this->mergeParams();

	}


    public function parseRequest()
    {
        $this->_request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );
        return true;
    }


    public function parseRouter()
    {

        if( !isset($this->resource['configFilenameRoutes']))
        {
            throw new \Exception("@configFilenameRoutes property is not defined in middleware definitions!", 1);
        }
        if(is_null($this->_request))
        {
            throw new \Exception("Request is not defined, try before call parseRequest", 1);
        }

        $routes = \Simple\Config\PHP::getScope($this->resource['configFilenameRoutes']);

        $router = new \Simple\Request\Router($routes);

        $this->_resources = $router->getResourcesByRequest($this->_request, 1);

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
    public function getFingerPrint() 
    {
        return md5(json_encode($this->_resources));
    }


    /**
     * Gets the value of _request.
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->_request;
    }


    private function mergeParams()
    {
        foreach ($this->_resources as $resource)
        {
            $this->_request->mergeParams($resource['params']);
        }
    }
}