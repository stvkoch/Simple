<?php

return array(

	//postRunController
	array(
		'route'=>'.*',
		'namespace' => '\Simple\Middleware\View',
		'class'=>'Cache',
		'function'=>'open',
		'id'=>'Simple.middleware.view.cache.open',
		'_run'=>true,
		'_continue'=>true
	),

	array(
		'route'=>'^!(login|logout)$',

		'namespace' => '\Frontend\Middleware', 
		'class'=>'Login',
		'function'=>'verify',
		'id'=>'front.mid.login.verify',
		'_run'=>true,
		'_continue'=>true
	),

	array(
		'route'=>'.*',
		'namespace' => '\Frontend\Middleware',
		'class'=>'Flash',
		'function'=>'openFlash',
		'id'=>'front.mid.flash.open',
		'_run'=>true,
		'_continue'=>true
	)

);
