<?php

namespace Simple\Middleware;


class Controller extends \Simple\Middleware\Base
{
	protected $response;

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