<?php namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerBladeExtensions();
    }

    private function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->directive('role', function ($arguments) {
                list($role, $guard) = explode(',', $arguments. ',');

                return "<?php if (auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });

            $blade->directive('endrole', function () {
                return "<?php endif; ?>";
            });

            $blade->directive('permission', function ($arguments) {
                list($role, $guard) = explode(',', $arguments. ',');

                return "<?php if (auth({$guard})->check() && auth({$guard})->user()->hasPermission({$role})): ?>";
            });

            $blade->directive('endpermission', function () {
                return "<?php endif; ?>";
            });
        });
    }
}
