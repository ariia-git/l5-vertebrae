<?php namespace App\Providers;

use App\Services\Localization\LocalizationService;
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
            'localization'
        ];
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(LocalizationService::class, function () {
            return new LocalizationService();
        });

        $this->app->alias(LocalizationService::class, 'localization');
    }
}
