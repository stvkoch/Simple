<?php

class RouterTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        \Simple\Config\PHP::setPath(__DIR__.'/config');

        $_SERVER['SERVER_NAME'] = 'phpunit.test';
    }

    public function testDisableWarning() {
        $this->assertEquals(true, true);
    }

    public function testMiddlewares()
    {
        $_SERVER['REQUEST_URI'] = '/admin/controller/function';
        $_SERVER['REQUEST_METHOD'] = 'TEST';
        $_SERVER['QUERY_STRING'] = 'foo=1&bar=2';
        $request = new \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );

        $routes = \Simple\Config\PHP::getScope('routesesource');

        $router = new \Simple\Request\Router( $routes );

        $resources = array(

            array(
                'namespace' => '\Simple\Middleware\View',
                'class'=>'Cache',
                'function'=>'open',
                'id'=>'Simple.middleware.view.cache.open'
            ),

            array(
                'namespace' => '\Frontend\Middleware',
                'class'=>'Flash',
                'function'=>'openFlash',
                'id'=>'front.mid.flash.open'
            ),

        );

        $resourcesFromRoute = $router->getResourcesByRequest($request);
        $arrayDiff = $this->_array_diff_multidimensional($resources, $resourcesFromRoute);
        $this->assertTrue(empty($arrayDiff));

    }


    //http://stackoverflow.com/questions/6026431/php-mutidimensional-array-diff
    private function _array_diff_multidimensional($arr1, $arr2) {
        $answer = array();
        foreach($arr1 as $k1 => $v1) {
            // is the key present in the second array?
            if (!array_key_exists($k1, $arr2)) {
               $answer[$k1] = $v1;
               continue;
            }

            // PHP makes all arrays into string "Array", so if both items
            // are arrays, recursively test them before the string check
            if (is_array($v1) && is_array($arr2[$k1])) {
                $answer[$k1] = $this->_array_diff_multidimensional($v1, $arr2[$k1]);
                continue;
            }

            // do the array_diff string check
            if ((string)$arr1[$k1] === (string)$arr2[$k1]) {
                continue;
            }

            // since both values are not arrays, and they don't match,
            // simply add the $arr1 value to match the behavior of array_diff
            // in the PHP core
            $answer[$k1] = $v1;
        }

        // done!
        return array_filter($answer);
    }


}