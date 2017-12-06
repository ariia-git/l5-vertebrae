<?php namespace App\Providers;

use App\Entities\Locale\Locale;
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
        $locales = app(Locale::class)->getLocalesForConfig();
        \Config::set('localization.activeLocales', $locales['active']);
        \Config::set('localization.supportedLocales', $locales['supported']);
        \Config::set('localization.hideDefaultLocaleInUrl', true);
        \Config::set('localization.useAcceptLanguageFilter', true);
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
