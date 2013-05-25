<?php

namespace Simple\Middleware;


class Controller extends \Simple\Middleware\Base
{
	protected $response;


    public function __construct($resource, &$backbone)
    {
        parent::__construct($resource, $backbone);
        $this->response = new \Simple\Response\HTTP($this->resource);
    }

    /**
     * Gets the value of _response.
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}