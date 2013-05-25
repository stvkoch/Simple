<?php
namespace Simple\Application;


class Backbone {

	protected $_resources = array();


	public function __construct($bootstrap=array())
	{
		foreach ($bootstrap as $callback) {
			$callback($this);
		}
	}


	public function setResource($id, &$resource)
	{
		$this->_resources[$id]=$resource;
	}

	public function getResourceById($id)
	{
		return $this->_resources[$id];
	}


	public function getResources()
	{
		return $this->_resources;
	}


	public function runResources($resources)
	{

			foreach ($resources as &$resource) {

				if(!isset($resource['id']))
					$resource['id'] = $resource['namespace'].'\\'.ucfirst($resource['class']);

				if(!isset($this->_resources[$resource['id']]))
				{
					$className = $resource['class'] = $resource['namespace'].'\\'.ucfirst($resource['class']);

					if(class_exists($className))
					{
						$this->_resources[$resource['id']] = new $className($resource, $this);
					}
					else
					{
						throw new \Exception("notFoundClassException $className", 404);
					}
				}

				$functionName = (isset($resource['function'])?$resource['function']:$resource['action'].'Action');

				if(!method_exists($this->_resources[$resource['id']], $functionName))
					throw new \Exception("notFoundFunctionException $functionName", 404);

				//extended from Simple\Middleware\Base
				// if(method_exists($this->_resources[$resource['id']], 'setBackbone'))
				// 	call_user_func_array(array($this->_resources[$resource['id']], 'setBackbone'), array(&$this));

				// if(method_exists($this->_resources[$resource['id']], 'setResource'))
				// 	call_user_func_array(array($this->_resources[$resource['id']], 'setResource'), array($resource));


				if(call_user_func(array($this->_resources[$resource['id']], $functionName))===false)
					break;
			}
	}
}
