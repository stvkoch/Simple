<?php
namespace Simple\Singleton;

abstract class Base implements \Simple\Singleton\Face{

	static private $_instance=null;

	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new static();
			self::$_instance->_init();
		}
		return self::$_instance;
	}

	static public function setInstance( \Simple\Singleton\Base $instance ){
		self::$_instance=$instance;
	}

	private function _init(){}
}

