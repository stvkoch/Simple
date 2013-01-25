# Simple Components


[![Build Status Simple stvkoch by travis-ci](https://api.travis-ci.org/stvkoch/Simple.png)](http://travis-ci.org/stvkoch/Simple)(green tests passed) (red tests failed)


Simple Components helps build their bundled applications simply. Unpretentious way we want to help you save time and less development efforts.



## Features

* \Simple\Config\Config
	It is a class that manages configuration files, you can use it as a DI containers.
* \Simple\Model\Model
	Basically helps build your SQL queries. Returning the data encapsulated in a class that helps you interact on loops and pager feature.


### \Simple\Config\Config

Returns the value of an attribute of a specific configuration file.

	\Simple\Config\Config::get('global', 'attributeName', 'defaultValue');

Set transversal configuration value, this is not presiste values on files.

	\Simple\Config\Config::set('global', 'attributeName', 'attributeValue');


#### Example config file

Example file configuration. config/database.php
	
	<?php
	return = array(
		'dsn' => 'mysql:dbname=testdb;host=127.0.0.1',
		'username' => 'yourUsername',
		'password' => 'passPassword',

		'handler' => function(){
			static $_handler = null;

			if(is_null($_handler)){
				try {
				    $_handler = new \PDO(
				    	\Simple\Config\Config::get('database', 'dsn'), 
				    	\Simple\Config\Config::get('database', 'username'), 
				    	\Simple\Config\Config::get('database', 'password')
				    );
				} catch (\PDOException $e) {
				    echo 'Connection failed: ' . $e->getMessage();
				}
			}
			return $_handler;
		}
	);



### \Simple\Model\Model

#### Example table

	CREATE  TABLE `users` (
		`id` BIGINT NOT NULL AUTO_INCREMENT ,
		`name` VARCHAR(45) NOT NULL ,
		`username` VARCHAR(45) NOT NULL ,
		`password` VARCHAR(45) NOT NULL ,
		PRIMARY KEY (`id`) 
	);
	CREATE  TABLE `posts` (
		`id` BIGINT NOT NULL AUTO_INCREMENT ,
		`userId` BIGINT NOT NULL ,
		`title` VARCHAR(245) NOT NULL ,
		`post` BLOB NOT NULL ,
		`metadata` BLOB NULL ,
		`createdDate` DATETIME NULL DEFAULT CURRENT_TIME ,
		PRIMARY KEY (`id`) 
	);


#### Models file

User Model

	<?php
	namespace \Model;

	class User extends \Simple\Model\Model {

	  public $tableName = 'users';

	  public $validations = array(
	  	'name' => array('myValRequired', 'myValLess(20)'),
	  	'username' => function( $opts ){
	  		if($opts['action']=='new' && (str_len($opts['value'])>20))
	  			new \Simple\Model\Exception\InvalidValue('Value are dummy');
	  	}
	  );

	  public $joinsMap = array(
	    'posts'=>'posts ON posts.userId=users.id',
	    'images'=>'images ON images.id=imagesUsers.imageId RIGHT JOIN imagesUsers.userId=users.id'
	  );

	  //generic find method
	  public function find($where='', $valuesBind=array(), $opts=array()){
	    return User()->select('users.*, images.*, count(highlights.id) as totalHighlights', $where, $valuesBind, $opts+array('left'=>array('highlight', 'images'), 'group'=>'users.id', 'order'=>'name'));//get all user wth paginator
	  }
	}
	//Alias User()
	function User(){
	  static $user;
	  if(!isset($user))
	    $user = new \Model\User();
	  
	  return $user;
	}

	function myValRequired( $opts ){
		if($opts['valeu']=='') new \Simple\Model\Exception\InvalidValue($opts['fieldName']' Required!');
	}
	function myValLess( $opts ){}

@Tests
- travis-ci-tests/Config/ConfigTest.php


this work in progress.