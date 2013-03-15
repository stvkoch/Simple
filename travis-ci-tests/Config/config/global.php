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
			$instance->count = $instance->singleton = 0;
			$instance->username = \Simple\Config\PHP::get('global', 'username', 'defaultValue');
			$instance->password = \Simple\Config\PHP::get('global', 'password', 'defaultValue');
			$instance->singleton++;
		}
		$instance->count++;
		return $instance;
	}
);
