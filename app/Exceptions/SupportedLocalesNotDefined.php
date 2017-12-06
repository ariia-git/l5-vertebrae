<?php namespace App\Exceptions;

class SupportedLocalesNotDefined extends \Exception
{
    public function __construct()
    {
        parent::__construct('Supported locales have not been defined.');
    }
}
