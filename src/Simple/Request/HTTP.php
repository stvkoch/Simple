<?php
namespace Simple\Request;
/**
* 
*/
class HTTP extends \Simple\Request\Base
{
	protected $_files = array();
	protected $_https = false;
	protected $_port = 80;
	protected $_serverName = null;
	protected $_router = null;


	/**
	* \Simple\Request\HTTP( $_SERVER, $_REQUEST, $_FILES );
	* \Simple\Request\Base( '/path/to/resource', 'foo=fooX&bar=barY', 'SIMPLE' );
	*/
	function __construct(&$server=array(), &$request=array(), &$files=array(), $router=null)
	{
		$this->_files = $files;
		$this->_serverName = $server["SERVER_NAME"];
		if (isset($server["HTTPS"]) && $server["HTTPS"] == "on") $this->_https = true;
		if (isset($server["SERVER_PORT"]) && $server["SERVER_PORT"] != "80") $this->_port = $server["SERVER_PORT"];
		if (isset($router)) $this->_router = $router;
		parent::__construct($server["REQUEST_URI"], $server["QUERY_STRING"], $server["REQUEST_METHOD"], $request );
	}




	public function hasFile()
	{
		return (boolean)count($this->_files);
	}

	public function getFile($fileName)
	{
		return $this->_files[$fileName];
	}

	public function getURL()
	{
		$pageURL = 'http';
		 if ($this->_https) {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ( $this->_port != "80") {
		  $pageURL .= $this->_serverName.":".$this->_port.$this->_uri;
		 } else {
		  $pageURL .= $this->_serverName.$this->_uri;
		 }
		 return $pageURL;
	}
	
}
