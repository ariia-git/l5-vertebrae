<?php namespace App\Http\Middleware\Localization;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleCookieRedirect extends Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     * @return $this|mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // requested URL should be ignored
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $params = explode('/', $request->path());
        $locale = $request->cookie('locale', false);

        if (count($params) > 0 && app('localiztion')->checkLocaleInActiveLocales($params[0])) {
            return $next($request)->withCookie(cookie()->forever('locale', $params[0]));
        }

        if ($locale && app('localization')->checkLocaleInActiveLocales($locale) && !(app('localization')->getDefaultLocale() === $locale && app('localization')->hideDefaultLocaleInUrl())) {
            $redirection = app('localization')->getLocalizedUrl($locale);
            $redirectResponse = new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);

            return $redirectResponse->withCookie(cookie()->forever('locale', $params[0]));
        }

        return $next($request);
    }
}
