<?php
namespace Example\Model;
/**
 * Model User
 * 
 * User()->find_user_logged(); //return false not logged on
 * User()->one('id=?', array(1));
 * User()->all('date>?', array('14-12-1975'), array('order'=>'id', 'page'=>1, 'offset'=>12'));
 */
include_once ROOT_DIR.'/lib/Model.php';

class User extends \Lib\Model{

  public $table_name = 'users';

  public $joins_map = array(
    'highlight'=>'highlights ON highlights.user_id=users.id',
    'images'=>'images ON images.id=images_users.image_id RIGHT JOIN images_users.user_id=users.id'
  );

  //generic find method
  public function find($where='', $values_bind=array(), $opts=array()){
    return User()->select('users.*, images.*, count(highlights.id) as total_highlights', $where, $values_bind, $opts+array('left'=>array('highlight', 'images'), 'group'=>'users.id', 'order'=>'name'));//get all user wth paginator
  }
}
//Alias User()
function User(){
  static $user;
  if(!isset($user))
    $user = new \Example\Model\User();
  
  return $user;
}