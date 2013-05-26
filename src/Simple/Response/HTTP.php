<?php

namespace Simple\Response;
/*
$response = new Response( $resource );


*/
class HTTP extends \Simple\Response\Base {

	protected $statusMessages = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );

    protected $contentType = array(
        'html'=>'text/html',
        'xml'=>'text/xml',
        'json'=>'application/json',
        'pdf'=>'application/pdf',
        'vcad'=>'text/vcard',
    );

    protected $contentFile;

    protected $content;

    protected $format;

    protected $cookie = array();

    public function __construct($resource)
    {
        $this->format = $resource['format'];
        $this->contentFile = str_replace(array('\Controller\\', '\\'), array('\View\\','/'), $resource['class']).'/'. strtolower($resource['action']);
    }


	public function redirect($url, $code=307)
	{
		header('Status: '.$this->statusMessages[$code]);
		header('Location: '.$url);
		exit;
	}


    /**
     * Gets the value of contentFile.
     *
     * @return mixed
     */
    public function getContentFile()
    {
        return $this->contentFile;
    }

    /**
     * Sets the value of contentFile.
     *
     * @param mixed $contentFile the contentFile
     *
     * @return self
     */
    public function setContentFile($contentFile)
    {
        $this->contentFile = $contentFile;

        return $this;
    }

    /**
     * Gets the value of format.
     *
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets the value of format.
     *
     * @param mixed $format the format
     *
     * @return self
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Gets the value of content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the value of content.
     *
     * @param mixed $content the content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function sendHeader()
    {
        header('Content-type: '.$this->contentType[$this->format]);
    }

}
