<?php

return array(
	array('route'=>'\.json','format'=>'json', '_continue'=>true, '_replace'=>''),
	array('route'=>'^/xpto/okidoki','controller'=>'firstController', 'action'=>'firstAction'),
    array('route'=>'^/([^/]+)Mod/([^/]+)/?([^/]+)/?(.+)?$','module'=>1, 'controller'=>2, 'action'=>3, 'params'=>4),
	array('route'=>'^/([^/]+)/?$','controller'=>'$1', 'action'=>'index'),
    array('route'=>'^/([^/]+)/([^/]+)/?(.*)$','controller'=>1, 'action'=>2, 'params'=>3),
);