<?php
namespace Example\Model;
/**
 * Model User
 * 

 * User()->one('id=?', array(1));
 * User()->all('date>?', array('14-12-1975'), array('order'=>'id', 'page'=>1, 'offset'=>12'));
 */

class User extends \Simple\Model\Model{

  public $tableName = 'users';

  public $joinsMap = array(
    'highlight'=>'highlights ON highlights.userId=users.id',
    'images'=>'images ON images.id=images_users.imageId RIGHT JOIN imagesUsers.userId=users.id'
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
    $user = new \Example\Model\User();
  
  return $user;
}
