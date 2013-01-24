<?php
return array(
	
	'projectName'=>'Simple',

	'username'=>'yourusername',
	'password'=>'yourpassword',

	'handlerPersistencePlace'=>function(){
		static $instance = null;
		if(is_null($instance)) 
		{
			$instance = new stdClass();
			$instance->username = \Simple\Config::get('global', 'username', 'defaultValue');
			$instance->password = \Simple\Config::get('global', 'password', 'defaultValue');
		}
		return $instance;
	}

);
