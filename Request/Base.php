<?php
namespace Simple\Request;
/**
* $request = new \Simple\Request\Base( $server["REQUEST_URI"], $server["QUERY_STRING"], $server["REQUEST_METHOD"], $_REQUEST  );
*/
class Base
{
	const POST = 'POST';
	const GET = 'GET';
	const PUT = 'PUT';
	const DELETE = 'DELETE';

	protected $_uri;
	protected $_query;
	protected $_method;
	protected $_params=array();

	/**
	* \Simple\Request\Base( '/path/to/resource', 'foo=fooX&bar=barY', 'REDIRECT', $_POST );
	*/
	function __construct($env=null, $query=null, $method=null, $params=array())
	{
		if(!is_null($env)) $this->_uri=$env;
		if(!is_null($query)) $this->_query=$query;
		if(!is_null($method)) $this->_method=$method;
		if(count($params)) $this->_params=$params;
		if(!is_null($query)) $this->_parameteriseQuery($this->_query);
	}

	protected function _parameteriseQuery($query)
	{
		$paramTmp=array();
		parse_str($query, $paramTmp);
		$this->_params = $paramTmp + $this->_params;
	}

	public function getParam($name)
	{
		return $this->_params[$name];
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function getMethod()
	{
		return $this->_method;
	}

}
