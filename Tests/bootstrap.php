<?php

include_once('./autoload.php');
// Register the directory to your include files
$loader = new ClassLoader();

// register classes with namespaces
$loader->add('Simple', __DIR__.'/../src');
$loader->add('Models', __DIR__.'/Model');
$loader->add('Lib', __DIR__.'/Singleton');
// activate the autoloader
$loader->register();