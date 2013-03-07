<?php

namespace Simple\Model\Validation;
/**
* '\Simple\Model\Validation\String::required', 
* '\Simple\Model\Validation\String::notLessThat(20)'),
* '\Simple\Model\Validation\String::equals(hello)'),
*/
class String
{

	static function required( $opts ){
		if(!isset($opts['value']) || is_null($opts['value']) || !is_string($opts['value']) )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' is required');
	}


	static function notLessThat( $opts ){
		if(!isset($opts['value']) || is_null($opts['value']) || strlen($opts['value']) < (int)$opts['config'] )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' field required more that '.$opts['config']. ' actually lenght are '.strlen($opts['value']));
	}


	static function contains( $opts ){

		if(!isset($opts['value']) )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' not equal that '.strlen($opts['value']));

		if(is_array($opts['config'])){
			if(!in_array($opts['value'], $opts['config']))
				throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' not equal that '.strlen($opts['value']));
		}elseif( $opts['value']!=$opts['config'] )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' not equal that '.strlen($opts['value']));
	}

}