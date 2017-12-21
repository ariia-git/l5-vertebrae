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
        $this->handleDbDefaultStringLength();

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

    /**
     * Limit the default string length setting if the current MySQL / MariaDB version does not support 255.
     */
    private function handleDbDefaultStringLength()
    {
        // check if defaultStringLength is going to work
        if (\DB::connection()->getDriverName() == 'mysql') {
            // check MySQL / MariaDB version
            $dbVersionQuery = \DB::select(\DB::raw('select version()'));

            if ($dbVersion = reset($dbVersionQuery)->{'version()'}) {
                if (strpos($dbVersion, 'Maria') !== false) {
                    // MariaDB
                    $minDbVersion = '10.2.2';

                    // we just need the version number
                    $dbVersion = substr($dbVersion, 0, strpos($dbVersion, '-Maria'));
                } else {
                    // MySQL
                    $minDbVersion = '5.7.7';
                }

                if ($dbVersion < $minDbVersion) {
                    // default string length needs to be limited
                    \Schema::defaultStringLength(191);
                }
            }
        }
    }
}
