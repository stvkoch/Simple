<?php
namespace Simple\Request;
/**
* $request = new \Simple\Request\Router( \Simple\Config\PHP::getScope('routes'), array('controller'=>'defaultControlerName', 'action'=>'defaultActionName') );
*/
class Router
{
	//default resource
	protected $_resource = array(
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
		if(!is_null($defaultResource)) $this->_resource = $defaultResource;
	}

	public function addRouters($routers)
	{
		$this->_routers = $routers + $this->_routers;
	}


	public function getResourceByURI($uri)
	{
		return $this->translate($uri);
	}

	public function translate($uri)
	{
		$resource = $this->_resource;
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
					if(is_int($positionMatch)){
						$resource[$resourceType] = $matches[$positionMatch];
					}elseif(strpos($positionMatch, '$')!==false){
						$resource[$resourceType] = preg_replace($regxURI, $positionMatch, $uri);
					}else{
						$resource[$resourceType] = $positionMatch;
					}
			    }
				if( !$_continue )
					break;
			}
		}

		if(is_string($resource['params']))
			parse_str($resource['params'], $resource['params']);

		return $resource;
	}
}