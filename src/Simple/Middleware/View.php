<?php

namespace Simple\Middleware;


class View extends \Simple\Middleware\Base
{

    private $_layout = '';
    private $_content = '';


	public function render()
	{
echo " RENDER \n";
		//var_dump($this->backbone->getResourceById('simple.controller'));
	}


    public function open()
    {
echo " OPEN \n";
        //var_dump($this->backbone->getResourceById('simple.request')->getFingerPrint());

    }


    public function save()
    {
echo " SAVE \n";
    }


    public function send(){

    }
}