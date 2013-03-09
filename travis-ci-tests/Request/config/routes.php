<?php
return array(
	'@\.json$@'=>array('format'=>'json'),
	'@^/xpto/okidoki$@'=>array('controller'=>'firstController', 'action'=>'firstAction'),
	'@^/[^/]+/show@'=>array('controller'=>'sameController', 'action'=>'show'),
);