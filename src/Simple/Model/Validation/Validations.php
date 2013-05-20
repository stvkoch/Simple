<?php
namespace Simple\Model\Validation;
/**
* Model pass by Validations value of field, fieldName of select query and 'config' value. Config field contain value that you insert on validation item in your model definition. 
* Ex: \Simple\Model\Validation\Validations::notLessThat(20) 20 is config value
* Ex: \Simple\Model\Validation\Validations::notLessThat(20) 20 is config value
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