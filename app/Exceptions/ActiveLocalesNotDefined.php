<?php namespace App\Exceptions;

class ActiveLocalesNotDefined extends \Exception
{
    public function __construct()
    {
        parent::__construct('Active locales have not been defined.');
    }
}
