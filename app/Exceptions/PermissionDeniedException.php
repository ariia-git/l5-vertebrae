<?php namespace App\Exceptions;

class PermissionDeniedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You do not have the required permission.');
    }
}
