<?php

require_once 'Config/Config.php';

require_once 'Model/Model.php';
require_once 'Model/Result/Result.php';

require_once 'Model/Validation/String.php';
require_once 'Model/Validation/Int.php';
require_once 'Model/Validation/Validations.php';

require_once 'Model/Exception/ValidationException.php';
require_once 'Model/Exception/InvalidValue.php';

require_once 'travis-ci-tests/Model/Models/User.php';

class ValidationsTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException \Simple\Model\Exception\InvalidValue
	 */
	public function testValidationsInsert(){
		$user = new \Models\User();
		$fields = array('name'=>'steven', 'role'=>'admin');
		$user->validation( $fields, array($user->validations_all, $user->validations_insert), 'new' );
		
	}

	/**
	 * @expectedException \Simple\Model\Exception\InvalidValue
	 */
	public function testValidationsString(){
		$user = new \Models\User();
		$fields = array('name'=>'steven', 'role'=>'admin');
		$user->validation( $fields, array(
			//validation_all
			array(
				'name' => array(
				  '\Simple\Model\Validation\String::contains([foo,bar])', //array
				  '\Simple\Model\Validation\String::required()',
				)
			),
			//validation_insert
		)
		, 'new' );
		
	}

	
	public function testValidationsString2(){
		try {
			
			
			$user = new \Models\User();
			$fields = array('name'=>'foo', 'role'=>'admin');
			$user->validation( $fields, array(
				//validation_all
				array(
					'name' => array(
					  '\Simple\Model\Validation\String::contains([foo,bar])', //array
					  '\Simple\Model\Validation\String::required()',
					)
				),
				//validation_insert
			)
			, 'new' );
		} catch (Exception $e) {

			$this->fail( $e->getMessage() );
		}
	}
}