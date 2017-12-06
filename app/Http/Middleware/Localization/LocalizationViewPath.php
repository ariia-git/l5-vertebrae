<?php namespace App\Http\Middleware\Localization;

use Illuminate\Http\Request;

class LocalizationViewPath extends Localization
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

        $currentLocale = app('localization')->getCurrentLocale();
        $viewPath = resource_path('views/' . $currentLocale);

        // add current locale code to view paths
        \View::addLocation($viewPath);

        return $next($request);
    }
}
