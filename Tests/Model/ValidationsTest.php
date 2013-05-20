<?php
require_once 'Singleton/Face.php';
require_once 'Singleton/Base.php';

require_once 'Config/Base.php';
require_once 'Config/PHP.php';

require_once 'Model/Base.php';
require_once 'Model/Result/Base.php';

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
		$class = new \ReflectionClass($user);
        $validations_all = $class->getProperty('validations_all');
        $validations_insert = $class->getProperty('validations_insert');
        $validations_all->setAccessible(true);
        $validations_insert->setAccessible(true);

        $validations_all_val = $validations_all->getValue($user);
        $validations_insert_val = $validations_insert->getValue($user);



		$fields = array('name'=>'steven', 'role'=>'admin');
		$user->validation( $fields, array($validations_all_val, $validations_insert_val), 'new' );

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