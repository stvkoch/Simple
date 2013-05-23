<?php
namespace Simple\Middleware;


class Application extends \Simple\Middleware\Base
{

	public function dispatch()
	{
		try{
			$resources = $this->backbone->getResourceById('simple.request')->getResources();

			$this->backbone->runResources($resources);
		} catch (\Exception $e) {
			$request = $this->backbone->getResourceById('simple.request')->getRequest();

			if($e->getCode()==404)
				\Simple\Response\HTTP::redirect( $request->getURL().'/404', 404 );
			else
				\Simple\Response\HTTP::redirect( $request->getURL().'/500', 500 );

		}
	}

	static function definition( $definition ) {
		if(!isset($definition['id'])||!isset($definition['class'])||!isset($definition['function']))
			throw new \Exception("@id, @class and @function has mandatary definition on middlewares routes", 1);

		return  $definition + array(
					'route'=>'.*',
					'namespace' => '\Simple\Middleware',
					'class'=>'_',
					'function'=>'_',
					'id'=>'simple._',

					'_run'=>true,
					'_continue'=>true,
					'_persist' => true
				);
	}
}