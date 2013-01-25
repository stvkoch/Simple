<?php

namespace Simple\Model\Exception;

class InvalidValue extends \Exception{
    public function __toString()
    {
      return $this->getMessage();
    }
}