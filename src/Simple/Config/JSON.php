<?php
namespace Simple\Config;
/**
 * Work like a container DI. And wrap config.
 * \Simple\Config\JSON::get(__CLASS__, 'attribute_name');
 *
 * @author steven koch <steven.koch@co.sapo.pt>
*/
class JSON extends \Simple\Config\Base{


  static public function readClassConfig( $className )
  {
    try {
      self::getInstance()->_config[$className] = json_decode( file_get_contents(self::getInstance()->_path . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) .'.json') ,true);
    } catch (Exception $e) {
      return false;
    }

  }


}
