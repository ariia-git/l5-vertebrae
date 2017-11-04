<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            //
        ];
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
