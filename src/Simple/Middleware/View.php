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

        $fingerPrint = $this->backbone->getResourceById('simple.request')->getFingerPrint();
        echo '<!--Finger Print or Cache Key '.$fingerPrint.'-->';
        echo "<pre>";

    }


    public function save()
    {
echo " SAVE \n";
    }


    public function send(){
echo " SEND \n";
    }
}