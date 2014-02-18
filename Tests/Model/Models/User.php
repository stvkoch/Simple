<?php
namespace Models;
/**
 * Model User
 *
 * User()->one('id=?', array(1));
 * User()->all('date>?', array('14-12-1975'), array('order'=>'id', 'page'=>1, 'offset'=>12'));
 */

class User extends \Simple\Model\Base{

  protected $_tableName = 'users';


  protected $_joinsMap = array(
    'highlight'=>'highlights ON highlights.userId=users.id',
    'images'=>'images ON images.id=images_users.imageId RIGHT JOIN imagesUsers.userId=users.id'
  );


  /**
   * \ClassName
   * $opts['fieldName']
   * $opts['action']
   * $opts['value']
   * $opts['config']
   */
  protected $_validations_all = array(
      'name' => array(
          '\Simple\Model\Validation\Validations::notLessThat(20)',
          '\Simple\Model\Validation\Validations::required()',
      )
  );
  protected $_validations_insert = array(
      'name' => array(
          '\Simple\Model\Validation\Validations::required', 
          '\Simple\Model\Validation\Validations::notLessThat(20)'
      ),
  );
  protected $_validations_update = array(
      'name' => array(
          '\Simple\Model\Validation\Validations::required', 
          '\Simple\Model\Validation\Validations::notLessThat(20)'
      ),
  );

  public function setPost($value='')
  {
    # code...
  }
  public function getPostByUserId( $userId )
  {
    return \Example\Model\Post()->find('user_id=?' , array($userId));
  }

  //generic find method
  public function find($where='', $valuesBind=array(), $opts=array())
  {
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