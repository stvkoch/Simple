<?php
namespace Simple\Singleton;

abstract class Base implements Face{

	static private $_instance=null;

	static public function getInstance(){
		if(is_null(self::$_instance)){
			$className = get_called_class();
			self::$_instance = new $className();
		}
		return self::$_instance;
	}
	static public function setInstance( \Simple\Singleton\Base $instance ){
		self::$_instance=$instance;
	}

}

