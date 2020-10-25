<?php

namespace App\Exception;

class NotFoundException extends \Exception
{
    protected $message = '404 Not Found';
}