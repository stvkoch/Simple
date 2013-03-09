<?php
return array(
	'@\.json$@'=>array('format'=>'json', '_continue'=>true),
	'@^/([^/]+)/([^/]+)/([^/]+)$@'=>array('module'=>'$1','controller'=>'$2', 'action'=>'firstaction'),
	'@^/xpto/okidoki$@'=>array('controller'=>'firstController', 'action'=>'firstAction'),
	'@^/[^/]+/show@'=>array('controller'=>'sameController', 'action'=>'show'),
);