<?php

namespace App\Exception;

class CustomException extends \Exception
{
    protected $field;

    public function __construct($message="", $field = NULL)
    {
        $this->field = $field;
        parent::__construct($message);
    }

    public function getField()
    {
        return $this->field;
    }
}