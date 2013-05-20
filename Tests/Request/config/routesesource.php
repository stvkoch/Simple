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
	),

	//mandatory
	array(
		'route'=>'.*',
		'namespace' => '\Simple\Middleware',
		'class'=>'Application',
		'function'=>'dispatch',
		'id'=>'simple.middleware.application',
		'_run'=>true,
		'_continue'=>true
	),

	//postRunController
	array(
		'route'=>'.*',
		'namespace' => '\Frontend\Middleware',
		'class'=>'Flash',
		'function'=>'saveFlash',
		'id'=>'front.mid.flash.save',
		'_run'=>true,
		'_continue'=>true
	),

	//postRunController
	array(
		'route'=>'.*',
		'namespace' => '\Simple\Middleware\View',
		'class'=>'Painter',
		'function'=>'render',
		'id'=>'Simple.middleware.view.painter.render',
		'_run'=>true,
		'_continue'=>true
	),

	//postRunController
	array(
		'route'=>'.*',
		'namespace' => '\Simple\Middleware\View',
		'class'=>'Cache',
		'function'=>'save',
		'id'=>'Simple.middleware.view.cache.save',
		'_run'=>true,
		'_continue'=>true
	),

);
