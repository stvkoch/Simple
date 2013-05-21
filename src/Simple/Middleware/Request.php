<?php

namespace Simple\Middleware;


class Request extends \Simple\Middleware\Base
{
	protected $_request;

	public function parse()
	{

		$this->_request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

		return true;
	}


    /**
     * Gets the value of _request.
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->_request;
    }
}