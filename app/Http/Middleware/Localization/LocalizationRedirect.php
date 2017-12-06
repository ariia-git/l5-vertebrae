<?php namespace App\Http\Middleware\Localization;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocalizationRedirect extends Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // requested URL should be ignored
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $urlLocale = $request->segment(1);
        // check for 2-segment locale (e.g.: us/en
        if ($request->segment(2) && app('localization')->checkLocaleInSupportedLocales($urlLocale . '/' . $request->segment(2))) {
            $urlLocale .= '/' . $request->segment(2);
        }

        $currentLocale = app('localization')->getCurrentLocale();
        $defaultLocale = app('localization')->getDefaultLocale();

        $locales = app('localization')->getActiveLocales();
        $hideDefaultLocale = app('localization')->hideDefaultLocaleInUrl();

        $redirection = false;
        if (!empty($locales[$urlLocale])) {
            if ($urlLocale === $defaultLocale && $hideDefaultLocale) {
                $redirection = app('localization')->getNonLocalizedUrl();
            }
        } elseif ($currentLocale !== $defaultLocale || !$hideDefaultLocale) {
            // the current URL does not contain any locale
            // the system redirects the user to the very same url "localized"
            $redirection = app('localization')->getLocalizedUrl(session('locale'), $request->fullUrl());
        }

        if ($redirection) {
            // save any flashed data for redirect
            app('session')->reflash();

            return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
        }

        return $next($request);
    }
}
