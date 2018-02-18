<?php namespace App\Exceptions;

class RoleDeniedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You do not have the required role.');
    }
}
