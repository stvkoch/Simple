<?php
namespace Simple\Middleware;


abstract class Base
{
	protected $backbone;
	protected $resource;

	public function __construct($resource=null, &$backbone=null)
    {
        $this->resource = $resource;
        $this->backbone = $backbone;
    }


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