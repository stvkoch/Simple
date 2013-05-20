<?php
namespace Simple\Application;


class Backbone {

	public $request;
	public $response;
	public $resources;



	public function __construct(\Simple\Request\Base $request=null, $response=null, $resource=null, $bootstrap=array())
	{
		$this->request=$request;
		$this->response=$response;
		$this->resources=$resource;
		foreach ($bootstrap as $callback) {
			$callback($this);
		}
	}

	public function setRequest($request)
	{
		$this->request=$request;
	}

	public function getRequest()
	{
		return $this->request;
	}

	public function setResponse($response)
	{
		$this->response=$response;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function setResource($resource)
	{
		$this->resources=$resource;
	}

	public function getResource()
	{
		return $this->resources;
	}

//	public function hasClass

	public function run()
	{
		$this->runResources($this->resources);
	}

	public function runResources($resources)
	{
		try {
			foreach ($resources as &$resource) {
				$className = $resource['class'] = $resource['namespace'].'\\'.ucfirst($resource['class']);

				if(class_exists($className)) {
					$obj = new $className();
				} else {
					throw new \Exception("notFoundClassException $className", 404);
				}
				$functionName = (isset($resource['function'])?$resource['function']:$resource['action'].'Action');

				if(!method_exists($obj, $functionName))
					throw new \Exception("notFoundFunctionException $functionName", 404);

				//extended from Simple\Middleware\Base
				if(method_exists($obj, 'setBackbone')) {
					call_user_func_array(array($obj, 'setBackbone'), array(&$this));
				}
				if(method_exists($obj, 'setCurrentResource')) {
					call_user_func_array(array($obj, 'setCurrentResource'), array($resource));
				}

				if(!call_user_func(array($obj, $functionName)))
					break;
			}
		} catch (\Exception $e) {
var_dump($e->getMessage(), $resource);exit;
			if($e->getCode()==404)
				\Simple\Response\HTTP::redirect( $this->request->getURL().'/404', 404 );
			else
				\Simple\Response\HTTP::redirect( $this->request->getURL().'/500', 500 );
		}
	}
}
