<?php
namespace Simple\Model\Validation;
/**
* '\Simple\Model\Validation\Int::required'
*/
class Int
{
	static function required( $opts ){
		if(!isset($opts['value']) || !is_integer($opts['value']) )
			throw new \Simple\Model\Exception\InvalidValue($opts['fieldName'] . ' is required');
	}

}