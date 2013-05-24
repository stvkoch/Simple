# Simple Components


[![Build Status Simple stvkoch by travis-ci](https://api.travis-ci.org/stvkoch/Simple.png)](http://travis-ci.org/stvkoch/Simple)(green tests passed) (red tests failed - bug found!)


Simple Components helps build their bundled applications simply. Unpretentious way we want to help you save time and less development efforts.



## Features

* \Simple\Config\PHP
	It is a class that manages configuration files, you can use it as a DI containers.
* \Simple\Model\Model
	Basically helps build your SQL queries. Returning the data encapsulated in a class that helps you interact on loops and pager feature.


## Install

### Via Composer

Install composer in your project:

	curl -s https://getcomposer.org/installer | php


Create a composer.json file in your project root:

	{
	    "require": {
	        "stvkoch/Simple": "*"
	    }
	}


Install via composer:

	php composer.phar install


Add this line to your application’s index.php file:

	<?php
	require 'vendor/autoload.php';


### Manual Install

	cd ~/yourprojectFolder/vendor
	git clone git@github.com:stvkoch/Simple.git Simple




### \Simple\Config\PHP

Returns the value of an attribute of a specific configuration file.

	\Simple\Config\PHP::get('global', 'attributeName', 'defaultValue');

Set transversal configuration value, this is not persiste values on files.

	\Simple\Config\PHP::set('global', 'attributeName', 'attributeValue');


#### Example config file
[config_file](#config_file)
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
				    	\Simple\Config\PHP::get('database', 'dsn'), 
				    	\Simple\Config\PHP::get('database', 'username'), 
				    	\Simple\Config\PHP::get('database', 'password')
				    );
				} catch (\PDOException $e) {
				    echo 'Connection failed: ' . $e->getMessage();
				}
			}
			return $_handler;
		}
	);




### \Simple\Request\HTTP

	$request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );
	$request->getURL();
	$request->getURI();
	$request->getParam('name');
	$request->getFile('filename');



### \Simple\Request\Router

	//config/routes.php
	<?php
	return array(
		array(
			'route'=>'@\.json$@',
			'format'=>'json',
			'_continue'=>true
		),
		array(
			'route'=>'@^/([^/]+)/([^/]+)@',
			'namespace'=>'\Controller\Frontend'
			'class'=>'$1',
			'action'=>'$2'),
	);


	$routes = \Simple\Config\PHP::getScope('routes');//get array with array routes
	$router = new \Simple\Request\Router( $routes );
	$resourceFromRoute = $router->getResourceByURI($request->getURI()); //return array represent one resource
	
	//@example:
	$resourceFromRoute = $router->getResourceByURI('/users/list.json');
	var_dump($resourceFromRoute);
	/*
	//Return same like this:
	[
		[
			'route'=>'@^/([^/]+)/([^/]+)@',
			'namespace'=>'\Controller\Frontend'
			'class'=>'users',
			'action'=>'list',
			'format'=>'json',
			'params'=>[]
		]
	]
	*/


### \Simple\Model\Model
[sql_file](#sql_file)
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


#### Models files
[model_file](#model_file)

User Model

//Model/User.php

	<?php
	namespace Model;

	class User extends \Simple\Model\Base {

		protected $tableName = 'users';

		protected $validations_all = array(
			'name' => array(
				'\Simple\Model\Validation\String::contains([foo,bar])',
				'\Simple\Model\Validation\String::required',
				'\Simple\Model\Validation\String::notLessThat(20)'
			),
		);
		protected $validations_insert = array(
			'name' => array(
				'\Simple\Model\Validation\String::required',
				'\Simple\Model\Validation\String::notLessThat(20)'
			),
		);
		protected $validations_update = array(
			'name' => array(
				'\Simple\Model\Validation\String::required',
				'\Simple\Model\Validation\String::notLessThat(20)'
			),
		);

		protected $joinsMap = array(
		'posts'=>'posts ON posts.userId=users.id',
		'images'=>'images ON images.id=imagesUsers.imageId RIGHT JOIN imagesUsers.userId=users.id'
		);


		/**
		 * $usersModel = new \Model\User();
		 * $users = $usersModel->find('id=?', array($userId));
		 * $posts = $users->getPost( $users[0]->id );
		 */
		public getPost($userId ){
			return \Model\Post::find('user_id=?', array($userId));
		}

		//generic find method
		public function find($where='', $valuesBind=array(), $opts=array()){
			return $this->select(
				'users.*, images.*, count(highlights.id) as totalHighlights',
				$where,
				$valuesBind,
				$opts+array('left'=>array('highlight', 'images'),
				'group'=>'users.id',
				'order'=>'name')
			);
		}

	}


Example usage User Model
[example_model_file](#example_model_file)

[See Config File](#config_file)

	<?php
	//Set where \Simple\Model find PDO handler, in this case \Simple\Config\PHP::get('database', 'handler')
	\Simple\Model\Base::$handlerConfigLocation = array('database', 'handler');


	$userModel = new \Model\User(); // or $userModel = \Model\User::instance();
	$usersResult = $userModel->find( 'id=?', array(1), array('limit'=>1) );
	$posts = $usersResult->getPost( $usersResult[0]->id );
	try{
		//prevent SQL injection by prepare statements, all queries are prepare statements and execute bind values
		$userModel->insert( array('name'=>$_POST['name'], 'email'=>$_POST['email'] ) );
	}catch( ValidationException $e ){
		foreach($e->getMessages() as $erros )
			echo $e->getMessage();
	}





### Tests

@Tests
- travis-ci-tests/Config/ConfigTest.php
	
	cd Simple
	phpunit.phar --debug .


Truly functional, but work in progress. New components are added constantly