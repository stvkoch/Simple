<?php
namespace Simple\Request;
/**
* 
*/
class HTTP extends \Simple\Request\Base
{

	/**
	* \Simple\Request\HTTP( $_SERVER, $_REQUEST );
	* \Simple\Request\Base( '/path/to/resource', 'foo=fooX&bar=barY', 'SIMPLE' );
	*
	*/
	function __construct($server=array(), $request=array())
	{

		$this->_uri = $server["REQUEST_URI"];
		$this->_query = $server["QUERY_STRING"];
		$this->_method = $server["REQUEST_METHOD"];
		$this->_params = $request;

		$this->_parameteriseQuery($this->_query);
	}


	public function hasFile()
	{
		# code...
	}

	public function getFile($fileName)
	{
		# code...
	}

}
