<?php
namespace Simple\Application;


class Backbone {

	private $_request;
	private $_response;
	private $_resources;


	public function __construct(\Simple\Request\Base $request=null, $response=null, $resource=null, $bootstrap)
	{
		$this->_request=$request;
		$this->_response=$response;
		$this->_resources=$resouces;
		foreach ($bootstrap as $callback) {
			$callback();
		}
	}

	public function setRequest($request)
	{
		$this->_request=$request;
	}

	public function getRequest()
	{
		return $this->_request;
	}

	public function setResponse($response)
	{
		$this->_response=$response;
	}

	public function getResponse()
	{
		return $this->_response;
	}

	public function setResource($resouces)
	{
		$this->_resources=$resouces;
	}

	public function getResource()
	{
		return $this->_resources;
	}

	public function run()
	{
		$this->runResources($this->_resources);
	}

	public function runResources($resources)
	{
		foreach ($resources as &$resource) {
			try {
				$className = $resource['namespace'].'\\'.$resource['class'];
				$obj = new $className();
				//extended from Simple\Middleware\Base
				if(method_exists($obj, 'setBackbone')) {
					call_user_func_array(array($obj, 'setBackbone'), array($this));
				}
				if(method_exists($obj, 'setCurrentResource')) {
					call_user_func_array(array($obj, 'setCurrentResource'), array($resource));
				}
				call_user_func_array(array($obj, $resource['function']));
			} catch (Exception $e) {
				$resource['_exception'] = $e;
			}
		}
	}
}
