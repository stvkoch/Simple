<?php
namespace Simple\Singleton;

interface Face{

	static public function getInstance();
	static public function setInstance( \Simple\Singleton\Base $instance );

}

