<?php
namespace Simple\Model\Exception;

class ValidationException extends \Exception
{
  
  private $msgs = array();

  function __construct($msgs)
  {
    $this->message = 'Try use @getMessages function';
    $this->msgs = $msgs;
  }


  //return array of excetions
  public function getMessages()
  {
    $this->msgs;
  }
  
}