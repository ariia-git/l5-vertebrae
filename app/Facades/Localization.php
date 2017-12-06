<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/** @see \App\Services\Localization\LocalizationService */
class Localization extends Facade
{
    /**
     * Get the registered name of the comonent.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'localization';
    }
}
