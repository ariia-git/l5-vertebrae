<?php namespace App\Http\Middleware\Localization;

use Illuminate\Http\Request;

class LocalizationRoutes extends Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // requested URL should be ignored
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $routeName = app('localization')->getRouteNameFromPath($request->getUri());

        app('localization')->setRouteName($routeName);

        return $next($request);
    }
}
