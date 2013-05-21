<?php

namespace Simple\Middleware;


class View extends \Simple\Middleware\Base
{

	public function render()
	{
echo " RENDER ";
        var_dump($this->backbone->getResourceById('simple.controller')->getResource());
		//var_dump($this->backbone->getResourceById('simple.controller')->getResponse());
	}

    public function open()
    {
        var_dump($this->backbone->getResourceById('simple.router')->getHash());
    }

    public function save()
    {
    }
}