<?php
namespace Simple\Middleware;


class Application extends \Simple\Middleware\Base
{

	public function dispatch()
	{
		try{
			$resources = $this->backbone->getResourceById('simple.router')->getResources();
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
		if(!isset($definition['id'])) throw new \Exception("@id has mandatary definition on middlewares routes", 1);

		return $definition + array(
					'route'=>'.*',
					'namespace' => '\Simple\Middleware',
					'class'=>'Application',
					'function'=>'_',
					'id'=>'simple.application',

					'_run'=>true,
					'_continue'=>true,
					'_persist' => true
				);
	}
}