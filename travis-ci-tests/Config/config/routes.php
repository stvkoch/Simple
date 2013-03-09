<?php
return array(
	'@\.json$@'=>array('format'=>'json'),
	'@^/$@'=>array('controller'=>'firstController', 'action'=>'firstAction'),
	'@^/[^/]+/show@'=>array('controller'=>'sameController', 'action'=>'show'),
);