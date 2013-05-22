<?php


class ModelTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
    {
    }

    public function testSingletonBase()
	{
		$this->assertInstanceOf('\Simple\Model\Base', \Models\User::instance() );
	}

	public function testSingletonUser()
	{
		$this->assertInstanceOf('\Models\User', \Models\User::instance() );
	}

	public function testInsertSQL()
	{
		$user = new \Models\User();
		$class = new \ReflectionClass($user);
        $method = $class->getMethod('_buildSthInsert');
        $method->setAccessible(true);
        $sqlTest = $method->invoke($user, array('name'=>'foo', 'role'=>'admin'));

		$sql = 'INSERT INTO users (name, role) VALUES (:name, :role)';
		$this->assertEquals( $sql , $sqlTest );
	}

	public function testInsertValues()
	{
		$user = new \Models\User();
		$class = new \ReflectionClass($user);
        $method = $class->getMethod('_buildSthBindParams');
        $method->setAccessible(true);
        $values = array('name'=>'foo', 'role'=>'admin');
        $bindValuesTest = $method->invoke($user, $values );

		$bindValues = array(':name'=>'foo', ':role'=>'admin');
		$this->assertEquals($bindValues, $bindValuesTest );
	}


	public function testUpdateSQL(){
		$user = new \Models\User();
		$class = new \ReflectionClass($user);
        $method = $class->getMethod('_buildSthUpdate');
        $method->setAccessible(true);
        $sqlTest = $method->invoke($user, array('nome'=>'foo'), 'id = ? AND date > ?' );


		$sql = "UPDATE 'users' SET  'nome'=:nome   WHERE id = ? AND date > ?";
		$this->assertEquals( $sql , $sqlTest );
	}

	public function testDeleteSQL(){
		$user = new \Models\User();
		$class = new \ReflectionClass($user);
        $method = $class->getMethod('_buildSthDelete');
        $method->setAccessible(true);
        $sqlTest = $method->invoke($user, 'id = ? AND date > ?' );


		$sql = "DELETE FROM users WHERE id = ? AND date > ?";
		$this->assertEquals( $sql , $sqlTest );
	}

	public function testCountSQL(){
		$user = new \Models\User();
		$class = new \ReflectionClass($user);
        $method = $class->getMethod('_buildSthSelectCount');
        $method->setAccessible(true);
        $sqlTest = $method->invoke($user, 'date>?', array('order'=>'id', 'page'=>0, 'group'=>'date') );


		$sql = "SELECT COUNT(*) AS total FROM users WHERE date>?";
		$this->assertEquals( $sql , $sqlTest );
	}

}