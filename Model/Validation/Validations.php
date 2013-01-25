<?php
namespace Simple\Model\Validation;
/**
* '\Simple\Model\Validation\Validations::required', 
* '\Simple\Model\Validation\Validations::notLessThat(20)'),
*/
class Validations
{
	static function required( $opts ){
		if(!isset($opts['value']) || is_null($opts['value']) || $opts['value']!='' )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' is required');
	}

	static function notLessThat( $opts ){
		if(!isset($opts['value']) || is_null($opts['value']) || strlen($opts['value']) < (int)$opts['config'] )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' field required more that '.$opts['config']. ' actually lenght are '.strlen($opts['value']));
	}

}