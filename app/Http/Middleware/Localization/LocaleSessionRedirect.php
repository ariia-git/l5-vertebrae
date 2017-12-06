<?php namespace App\Http\Middleware\Localization;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleSessionRedirect extends Localization
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

        $params = explode('/', $request->path());
        $locale = session('locale', false);

        if (count($params) > 0 && app('localization')->checkLocaleInActiveLocales($params[0])) {
            session(['locale' => $params[0]]);

            return $next($request);
        }

        if ($locale && app('localization')->checkLocaleInActiveLocales($locale) && !(app('localization')->getDefaultLocale() === $locale && app('localization')->hideDefaultLocaleInUrl())) {
            app('session')->reflash();
            $redirection = app('localization')->getLocalizedUrl($locale);

            return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
        }

        return $next($request);
    }
}
