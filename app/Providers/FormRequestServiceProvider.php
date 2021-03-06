<?php namespace App\Providers;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(AbstractFormRequest::class, function ($app) {
            $routeParts = explode('@', $app['router']->currentRouteAction());
            $routeAction = end($routeParts);

            $classParts = explode('\\', reset($routeParts));
            $className = end($classParts);
            $modelName = str_replace('Controller', '', $className);

            $formRequestVerb = '';
            if ($routeAction == 'store') {
                $formRequestVerb = 'Create';
            } else if ($routeAction == 'update') {
                $formRequestVerb = 'Update';
            }

            $formRequestName = $formRequestVerb . $modelName . 'Request';

            return $app->make('\App\Http\Requests\\' . $modelName . '\\' . $formRequestName);
        });
    }
}
