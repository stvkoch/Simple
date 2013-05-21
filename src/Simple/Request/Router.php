<?php
namespace Simple\Request;
/**
* $request = new \Simple\Request\Router( \Simple\Config\PHP::getScope('routes'), array('controller'=>'defaultControlerName', 'action'=>'defaultActionName') );
*/
class Router
{
	protected $_request = null;

	//default resource
	protected $_resource = array(
		'namespace'=>'\Frontend',
		'class'=>'index',
		'action'=>'index',
		'format'=>'html',
		'id'=>'simple.controller',
		'params'=>array()
	);

	protected $_routes=array(
		array(
			'route'=>'^([^\/]+)\/?$',
			'class'=>'$1',
			'action' =>'index'
		),
		/*Controller e action por defeito "" or "/"*/
		array(
			'route'=>'^\/?$',
			'class' => 'index',
			'action' => 'index'
		)
	);

	public function __construct(array $routes, $defaultResource=null)
	{
		$this->addRoutes($routes);
		if(!is_null($defaultResource)) $this->_resource = $defaultResource;
	}

	public function addRoutes($routes)
	{
		$this->_routes = $routes + $this->_routes;
	}


	public function getResourcesByURI($uri)
	{
		return $this->getResources($uri);
	}

	public function getResourcesByRequest( \Simple\Request\Base &$request)
	{
		$this->_request = $request;
		return $this->getResources($request->getURI());
	}

	public function getDefaultResource()
	{
		$resource = $this->_resource;
		$resource['params'] = $_REQUEST;
		return $resource;
	}

	public function getResources($uri)
	{
		$resources = array();
		$resource = $this->getDefaultResource();
		$routes = $this->_routes;
		$_continue = false;

		foreach ($routes as $resourceBase)
		{

			$regxRoute = $resourceBase['route'];
			unset($resourceBase['route']);

			if(preg_match('@'.$regxRoute.'@', $uri, $matches))
			{
				foreach ($resourceBase as $resourceType => $positionMatch)
				{
					if(is_int($positionMatch)){
						if(isset($matches[$positionMatch])) $resource[$resourceType] = $matches[$positionMatch];
					}elseif(strpos($positionMatch, '$')!==false){
						$resource[$resourceType] = preg_replace('@'.$regxRoute.'@', $positionMatch, $uri);
					}else{
						$resource[$resourceType] = $positionMatch;
					}
			    }

			    if(isset($resourceBase['_continue']))
			    {
					$_continue = $resourceBase['_continue'];
					unset($resourceBase['_continue']);
					//unset($resource['_continue']);
				}

				if(isset($resourceBase['_replace']))
			    {
					$uri = preg_replace('@'.$regxRoute.'@', $resourceBase['_replace'], $uri);
					unset($resourceBase['_replace']);
					//unset($resource['_replace']);
				}

				if( is_string($resource['params']) ) {
					$resource['params'] = array_filter(explode('/', $resource['params']));
					if (!is_null($this->_request)) {
						$this->_request->mergeParams($resource['params']);
					}
				}

				if(isset($resourceBase['_run']) && $resourceBase['_run'])
			    {
					$resources[] = $resource;
				}

				if( !$_continue )
				{
					break;
				}else{
					$resource = $this->getDefaultResource();//next resource, set default
				}
			}
		}

		if(!count($resources)) $resources = array($resource);

		if (!is_null($this->_request)) {
			$this->_request->setResources($resources);
		}

		return $resources;
	}
}