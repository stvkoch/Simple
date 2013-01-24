<?php
namespace Simple;
/**
 * Work like a container DI. And wrap config.
 * \Simple\Config\Config::readGlobalConfig();
 * \Simple\Config\Config::get(__CLASS__, 'attribute_name');
 * 
 * @author steven koch <steven.koch@co.sapo.pt>
*/
class Config {

  static $_config = array();

  static $_path = 'config' ;


  static public function setPath( $newPath )
  {
    self::$_path = $newPath;
  }

  static public function readGlobalConfig()
  {
    return self::readClassConfig('config');
  }

  static public function readClassConfig( $className )
  {
    try {

      self::$_config[$className] = include( self::$_path . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) .'.php' );

    } catch (Exception $e) {
      return false;
    }

  }

  static public function getScope( $scope )
  {
    if(!isset(self::$_config[$scope])) self::readClassConfig( $scope );
    return self::$_config[$scope];
  }

  static public function get( $scope, $attributeName, $default=null )
  {

    if(!isset(self::$_config[$scope])) self::readClassConfig( $scope );
    $value = isset(self::$_config[$scope][$attributeName]) ? self::$_config[$scope][$attributeName] : $default ;
    return (is_object($value)) ? $value() : $value;
  }

  static public function set( $scope, $attributeName, $value )
  {
    return self::$_config[$scope][$attributeName] = $value;
  }

}
