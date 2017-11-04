<?php namespace App\Providers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app_path('\Helpers\*.php')) as $helper) {
            require_once $helper;
        }

        if ($this->app->isLocal()) {
            $providers = config('app.dev-providers');

            foreach ($providers as $provider) {
                $this->app->register($provider);
            }
        }

        $this->app->alias('bugsnag.logger', Log::class);
        $this->app->alias('bugsnag.logger', LoggerInterface::class);
    }
}
