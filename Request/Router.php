<?php
namespace Simple\Request;
/**
* $request = new \Simple\Request\Router( \Simple\Config\PHP::getScope('routes'), array('controller'=>'defaultControlerName', 'action'=>'defaultActionName') );
*/
class Router
{
	protected $_resourceId = array(
		'module'=>'Frontend',
		'controller'=>'index',
		'action'=>'index',
		'format'=>'html',
		'params'=>array()
	);

	protected $_routers=array(
		'/^(\w+)(\/$|$)/'=> array(
			'controller'=>'$1',
			'action' =>'index'
		),
		/*Controller e action por defeito "" or "/"*/
		'/^(\/$|$)/'=>array( 
			'controller' => 'index',
			'action' => 'index'
		)
	);

	public function __construct(array $routers, $defaultResource=null)
	{
		$this->addRouters($routers);
		if(!is_null($defaultResource)) $this->_resourceId = $defaultResource;
	}

	public function addRouters($routers)
	{
		$this->_routers = $routers + $this->_routers;
	}


	public function getResourceByURI($uri)
	{
		return $this->translate($uri);
		$uri = $this->translate($uri);

		$resourceId = $this->_resourceId;

		if(0===($pos = strpos($uri, '/')))
			$uri = substr($uri, 1);
		$paramsURS = explode('/', $uri);

		$controller = array_shift($paramsURS);
		if($controller)
		    $resourceId['controller'] = ucfirst( strtolower($controller) );
		$action = array_shift($paramsURS);
		if($action)
		    $resourceId['action'] = strtolower($action);
		$resourceId['params'] = $paramsURS;
		return $resourceId;
	}

	public function translate($uri)
	{
		$resourceId = $this->_resourceId;
		$routers = $this->_routers;
		$_continue = false;
		foreach ($routers as $regxURI => $route)
		{

			if(preg_match( $regxURI, $uri, $matches))
			{
				if(isset($route['_continue'])){
					$_continue = $route['_continue'];
					unset($route['_continue']);
				}
				foreach ($route as $resourceType => $positionMatch) 
				{
					if(preg_match('/\$([0-9]+)/',$positionMatch, $position))
						$resourceId[$resourceType] = $matches[(int)$position[1]];
					else
						$resourceId[$resourceType] = $positionMatch;
			    }
				if( !$_continue )
					break;
			}
		}

		if(is_string($resourceId['params']))
			parse_str($resourceId['params'], $resourceId['params']);

		return $resourceId;
	}
}