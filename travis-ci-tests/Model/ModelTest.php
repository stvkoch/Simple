<?php

require_once 'Config/Config.php';

require_once 'Model/Model.php';
require_once 'Model/Result/Result.php';

require_once 'Model/Validation/Validations.php';

require_once 'Model/Exception/ValidationException.php';
require_once 'Model/Exception/InvalidValue.php';

require_once 'travis-ci-tests/Model/Models/User.php';

class ModelTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    }

	public function testInsertSQL()
	{
		$user = new \Models\User();
		$sql = 'INSERT INTO users (name, role) VALUES (:name, :role)';
		$this->assertEquals( $sql , $user->_buildSthInsert(array('name'=>'foo', 'role'=>'admin')) );
	}

	public function testInsertValues()
	{
		$user = new \Models\User();
		$bindValues = array(':name'=>'foo', ':role'=>'admin');
		$values = array('name'=>'foo', 'role'=>'admin');
		$this->assertEquals($bindValues, $user->_buildSthBindParams( $values ) );
	}


	public function testUpdateSQL(){
		$user = new \Models\User();
		$sql = "UPDATE 'users' SET  'nome'=:nome   WHERE id = ? AND date > ?";
		$this->assertEquals( $sql , $user->_buildSthUpdate(array('nome'=>'foo'), 'id = ? AND date > ?') );
	}

	public function testDeleteSQL(){
		$user = new \Models\User();
		$sql = "DELETE FROM users WHERE id = ? AND date > ?";
		$this->assertEquals( $sql , $user->_buildSthDelete('id = ? AND date > ?') );
	}

	public function testCountSQL(){
		$user = new \Models\User();
		$sql = "SELECT COUNT(*) AS total FROM users WHERE date>?";
		$this->assertEquals( $sql , $user->_buildSthSelectCount('date>?', array('order'=>'id', 'page'=>0, 'group'=>'date')) );
		
	}

}