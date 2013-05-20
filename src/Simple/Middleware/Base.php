<?php
namespace Simple\Middleware;


abstract class Base
{
	protected $app;
	protected $resource;

	public function setBackbone(&$app)
	{
		$this->app = $app;
	}

	public function setCurrentResource($resource)
	{
		$this->resource = $resource;
	}
}