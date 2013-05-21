<?php
namespace Simple\Middleware;


abstract class Base
{
	protected $backbone;
	protected $resource;

	public function setBackbone(&$backbone)
	{
		$this->backbone = $backbone;
	}

	public function setResource($resource)
	{
		$this->resource = $resource;
	}
	public function getResource()
	{
		return $this->resource;
	}
}