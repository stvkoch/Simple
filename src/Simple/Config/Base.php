<?php
namespace Simple\Config;
/**
 * Work like a container DI. And wrap config.
 * \Simple\Config\PHP::get(__CLASS__, 'attribute_name');
 * 
 * @author steven koch <steven.koch@co.sapo.pt>
*/
class Base extends \Simple\Singleton\Base{

  static $_config = array();

  static $_path = 'config' ;


  static public function setPath( $newPath )
  {
    self::getInstance()->_path = $newPath;
  }

  static public function getScope( $scope )
  {
    if(!isset(self::getInstance()->_config[$scope])) self::getInstance()->readClassConfig( $scope );
    return self::getInstance()->_config[$scope];
  }

  static public function get( $scope, $attributeName, $default=null )
  {
    if(is_array($scope))
    {
      if(isset($scope[2])) $default = $scope[2];
      $attributeName = $scope[1];
      $scope = $scope[0];
    }

    if(!isset(self::getInstance()->_config[$scope])) self::getInstance()->readClassConfig( $scope );
    $value = isset(self::getInstance()->_config[$scope][$attributeName]) ? self::getInstance()->_config[$scope][$attributeName] : $default ;
    return (is_object($value)) ? $value() : $value;
  }

  static public function set( $scope, $attributeName, $value )
  {
    return self::getInstance()->_config[$scope][$attributeName] = $value;
  }

  static public function equal( $scope, $attributeName, $value ) {
    return (self::getInstance()->get($scope, $attributeName)===$value);
  }

  // static public function instanceOf( $scope, $attributeName, $object ) {
  //   return (self::getInstance()->get($scope, $attributeName) instanceof $object);
  // }

}
