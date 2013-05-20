<?php
namespace Simple\Middleware;


abstract class Base
{
	private $_app;
	private $_resource;

	public function setBackbone(&$app)
	{
		$this->_app = $app;
	}

	public function setCurrentResource(&$resource)
	{
		$this->_resource = $resource;
	}
}