<?php
namespace Simple\Config;
/**
 * Work like a container DI. And wrap config.
 * \Simple\Config\PHP::get(__CLASS__, 'attribute_name');
 * 
 * @author steven koch <steven.koch@co.sapo.pt>
*/
class PHP extends \Simple\Config\Base {


  static public function readClassConfig( $className )
  {
    try {

      self::getInstance()->_config[$className] = include( self::getInstance()->_path . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) .'.php' );

    } catch (Exception $e) {
      return false;
    }

  }


}
