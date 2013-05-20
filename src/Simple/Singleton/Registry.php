<?php
namespace Simple\Singleton;

class Registry{
	static public function instanciate( \Simple\Singleton\Base $instance ){
		$class = get_class($instance);
		call_user_func(array($class, 'setInstance'), $instance);
		return $instance;
	}
}