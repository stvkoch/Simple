<?php
namespace Simple\Utils;

/**
 * in examples above replace 001010100 by variable name
 * This class encapsule somes useful functions to help in bitwise operations
 *
 *
 */
class Bwise {

	protected $value;

	public function __construct( $value ) {
		$this->value=$value;
	}

	/**
	* @example
	* 001010100 = Bwise::createFromString('001010100');
	* @return Bwise
	*/
	static public function createFromString( $string ) {
		return new self( intval($string, 2) );
	}

	/**
	* @example
	* 001010100->getLastBit()->print(); //001000000
	* @return Bwise
	*/
	public function getLastBit( ) {
		return new self( pow( 2, (int)log($this->value,2) ) );
	}

	/**
	* @example
	* 001010100->getFirstBit()->print(); //000000100
	* @return Bwise
	*/
	public function getFirstBit( ) {
		return new self( $this->value ^ ( $this->value & ($this->value-1 ) ) );
	}

	/**
	* @example
	* 001010100->belogns( 000000001 | 001000000 ); //true
	* 001010100->belogns( 100000001 | 010000010 ); //false
	* @return Boolean
	*/
	public function belogns( $mask ) {
		return (bool) $this->value & $mask;
	}

	/**
	* @example
	* 001010100->hasOnlyOneBit( ); //false
	* 010000000->hasOnlyOneBit( ); //true
	* @return Boolean
	*/
	public function hasOnlyOneBit( ) {
		return (($this->value & ( $this->value -1 ))===0);
	}

	/**
	 * print usaded format bit
	 */
	public function print( ) {
		echo $this->sprint();
	}

	/**
	 * return value formated like bit
	 */
	public function sprint( ) {
		print('%b', $this->value);
	}

	/**
	 * echo 001010100
	 */
	public function __toString( ) {
		return $this->value;
	}

	/**
	 * useful in cases:
	 *  001010100() & 001..
	 */
	public function __invoke( ) {
		return $this->value;
	}
}