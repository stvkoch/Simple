<?php
namespace Lib;

class Foo extends \Simple\Singleton\Base{

	public $h='bar';

	function __construct($h){
		$this->h=$h;
	}

}