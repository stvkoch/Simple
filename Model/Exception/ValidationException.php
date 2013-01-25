<?php
namespace Simple\Model\Exception;

class ValidationException
{
  
  private $msgs;

  function __construct($msgs)
  {
    $this->msgs = $msgs;
  }

  //return array of excetions
  public function getMessage()
  {
    $this->msgs;
  }
  
}