<?php
namespace Simple\Request;
/**
* 
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
	* \Simple\Request\HTTP( '/path/to/resource', 'foo=fooX&bar=barY', 'REDIRECT', $_POST );
	*/
	function __construct($env=null, $query=null, $method=null, $params=array())
	{
		$this->_uri=$env;
		$this->_query=$query;
		$this->_method=$method;
		$this->_params=$params;
		$this->_parameteriseQuery($this->_query);
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
