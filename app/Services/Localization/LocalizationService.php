<?php namespace App\Services\Localization;

use App\Exceptions\ActiveLocalesNotDefined;
use App\Exceptions\SupportedLocalesNotDefined;
use App\Exceptions\UnsupportedLocaleException;

class LocalizationService
{
    /**
     * Config repository.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Illuminate view Factory.
     *
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * Illuminate translator class.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Illuminate router class.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Illuminate request class.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Illuminate url class.
     *
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    /**
     * Illuminate request class.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Illuminate request class.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Active Locales.
     *
     * @var array
     */
    protected $activeLocales;

    /**
     * Supported Locales.
     *
     * @var array
     */
    protected $supportedLocales;

    /**
     * Current locale.
     *
     * @var string
     */
    protected $currentLocale = false;

    /**
     * An array that contains all routes that should be translated.
     *
     * @var array
     */
    protected $translatedRoutes = [];

    /**
     * Name of the translation key of the current route, it is used for url translations.
     *
     * @var string
     */
    protected $routeName;

    /**
     * Creates new instance.
     *
     * @throws ActiveLocalesNotDefined
     * @throws SupportedLocalesNotDefined
     * @throws UnsupportedLocaleException
     */
    public function __construct()
    {
        $this->app = app();

        $this->config = $this->app['config'];
        $this->view = $this->app['view'];
        $this->translator = $this->app['translator'];
        $this->router = $this->app['router'];
        $this->request = $this->app['request'];
        $this->url = $this->app['url'];

        // set default locale
        $this->defaultLocale = $this->config->get('app.locale');
        $activeLocales = $this->getActiveLocales();
        $supportedLocales = $this->getSupportedLocales();

        if (empty($activeLocales[$this->defaultLocale])) {
            throw new UnsupportedLocaleException('Laravel default locale is not in the activeLocales array.');
        }

        if (empty($supportedLocales[$this->defaultLocale])) {
            throw new UnsupportedLocaleException('Laravel default locale is not in the supportedLocales array.');
        }
    }

    /**
     * Check if Locale exists on the active locales array.
     *
     * @param string|bool $locale
     * @return bool
     * @throws ActiveLocalesNotDefined
     */
    public function checkLocaleInActiveLocales($locale)
    {
        $locales = $this->getActiveLocales();
        if ($locale !== false && empty($locales[$locale])) {
            return false;
        }

        return true;
    }

    /**
     * Check if Locale exists on the supported locales array.
     *
     * @param string|bool $locale
     * @return bool
     * @throws SupportedLocalesNotDefined
     */
    public function checkLocaleInSupportedLocales($locale)
    {
        $locales = $this->getSupportedLocales();
        if ($locale !== false && empty($locales[$locale])) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if the string given is a valid url.
     *
     * @param string $url
     * @return bool
     */
    protected function checkUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Create an url from the uri.
     *
     * @param string $uri
     * @return string
     */
    public function createUrlFromUri($uri)
    {
        $uri = ltrim($uri, '/');
        if (empty($this->baseUrl)) {
            return app('url')->to($uri);
        }

        return $this->baseUrl . $uri;
    }

    /**
     * Extract attributes for current url.
     *
     * @param bool|false|null|string $url
     * @param string                 $locale
     * @return array
     */
    protected function extractAttributes($url = false, $locale = '')
    {
        if (!empty($url)) {
            $attributes = [];

            $parse = parse_url($url);
            if (isset($parse['path'])) {
                $parse = explode('/', $parse['path']);
            } else {
                $parse = [];
            }

            $url = [];
            foreach ($parse as $segment) {
                if (!empty($segment)) {
                    $url[] = $segment;
                }
            }

            foreach ($this->router->getRoutes() as $route) {
                $path = method_exists($route, 'uri') ? $route->uri() : $route->getUri();
                if (!preg_match("/{[\w]+}/", $path)) {
                    continue;
                }

                $path = explode('/', $path);

                $i = 0;
                $match = true;
                foreach ($path as $j => $segment) {
                    if (isset($url[$i])) {
                        if ($segment === $url[$i]) {
                            $i++;
                            continue;
                        }

                        if (preg_match("/{[\w]+}/", $segment)) {
                            // must-have parameters
                            $attributeName = preg_replace(['/}/', '/{/', "/\?/"], '', $segment);
                            $attributes[$attributeName] = $url[$i];
                            $i++;
                            continue;
                        }

                        if (preg_match("/{[\w]+\?}/", $segment)) {
                            // optional parameters
                            if (!isset($path[$j + 1]) || $path[$j + 1] !== $url[$i]) {
                                // optional parameter taken
                                $attributeName = preg_replace(['/}/', '/{/', "/\?/"], '', $segment);
                                $attributes[$attributeName] = $url[$i];
                                $i++;
                                continue;
                            }
                        }
                    } elseif (!preg_match("/{[\w]+\?}/", $segment)) {
                        // no optional parameters but no more $url given
                        // this route does not match the url
                        $match = false;
                        break;
                    }
                }

                if (isset($url[$i + 1])) {
                    $match = false;
                }

                if ($match) {
                    return $attributes;
                }
            }
        } else {
            if (!$this->router->current()) {
                return [];
            }

            $attributes = $this->normalizeAttributes($this->router->current()->parameters());

            $response = event('routes.translation', [$locale, $attributes]);
            if (!empty($response)) {
                $response = array_shift($response);
            }

            if (is_array($response)) {
                $attributes = array_merge($attributes, $response);
            }
        }

        return $attributes;
    }

    /**
     * Returns the translated route for the path and the url given.
     *
     * @param string $path
     * @param string $urlLocale
     * @return string|bool
     */
    protected function findTranslatedRouteByPath($path, $urlLocale)
    {
        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            if ($this->translator->trans($translatedRoute, [], $urlLocale) == rawurldecode($path)) {
                return $translatedRoute;
            }
        }

        return false;
    }

    /**
     * Returns the translated route for an url and the attributes given and a locale.
     *
     * @param string|bool|null $url
     * @param array            $attributes
     * @param string           $locale
     * @return string|bool
     * @throws ActiveLocalesNotDefined
     * @throws SupportedLocalesNotDefined
     * @throws UnsupportedLocaleException
     */
    protected function findTranslatedRouteByUrl($url, array $attributes, $locale)
    {
        if (empty($url)) {
            return false;
        }

        // check if this url is a translated url
        foreach ($this->translatedRoutes as $translatedRoute) {
            $routeName = $this->getUrlFromRouteNameTranslated($locale, $translatedRoute, $attributes);
            if ($this->getNonLocalizedUrl($routeName) == $this->getNonLocalizedUrl($url)) {
                return $translatedRoute;
            }
        }

        return false;
    }

    /**
     * Return an array of all active Locales.
     *
     * @return array
     * @throws ActiveLocalesNotDefined
     */
    public function getActiveLocales()
    {
        if (!empty($this->activeLocales)) {
            return $this->activeLocales;
        }

        $locales = $this->config->get('localization.activeLocales');
        if (empty($locales) || !is_array($locales)) {
            throw new ActiveLocalesNotDefined();
        }

        $this->activeLocales = $locales;

        return $locales;
    }

    /**
     * Returns the config repository for this instance.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns current language.
     *
     * @return mixed|string
     * @throws ActiveLocalesNotDefined
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale) {
            return $this->currentLocale;
        }

        if ($this->useAcceptLanguageHeader()) {
            $negotiator = new LanguageNegotiator($this->defaultLocale, $this->getActiveLocales(), $this->request);

            return $negotiator->negotiateLanguage();
        }

        // get application default language
        return $this->config->get('app.locale');
    }

    /**
     * Returns current locale direction.
     *
     * @return string
     * @throws ActiveLocalesNotDefined
     */
    public function getCurrentLocaleDirection()
    {
        if (!empty($this->supportedLocales[$this->getCurrentLocale()]['dir'])) {
            return $this->supportedLocales[$this->getCurrentLocale()]['dir'];
        }

        switch ($this->getCurrentLocaleScript()) {
            // Other (historic) RTL scripts exist, but this list contains the only ones in current use.
            case 'Arab':
            case 'Hebr':
            case 'Mong':
            case 'Tfng':
            case 'Thaa':
                return 'rtl';
            default:
                return 'ltr';
        }
    }

    /**
     * Returns current locale name.
     *
     * @return string
     * @throws ActiveLocalesNotDefined
     */
    public function getCurrentLocaleName()
    {
        return $this->supportedLocales[$this->getCurrentLocale()]['name'];
    }

    /**
     * Returns current locale native name.
     *
     * @return string
     * @throws ActiveLocalesNotDefined
     */
    public function getCurrentLocaleNative()
    {
        return $this->supportedLocales[$this->getCurrentLocale()]['native'];
    }

    /**
     * Returns current regional.
     *
     * @return string
     * @throws ActiveLocalesNotDefined
     */
    public function getCurrentLocaleRegional()
    {
        // need to check if it exists
        if (isset($this->supportedLocales[$this->getCurrentLocale()]['regional'])) {
            $regional = $this->supportedLocales[$this->getCurrentLocale()]['regional'];

            return str_replace('-', '_', $regional);
        } else {
            return;
        }
    }

    /**
     * Returns current locale script.
     *
     * @return string
     * @throws ActiveLocalesNotDefined
     */
    public function getCurrentLocaleScript()
    {
        return $this->supportedLocales[$this->getCurrentLocale()]['script'];
    }

    /**
     * Returns default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Returns the locale's regional.
     *
     * @param $locale
     * @return mixed
     */
    public function getLocaleRegional($locale)
    {
        // need to check if it exists
        if (isset($this->supportedLocales[$locale]['regional'])) {
            $regional = $this->supportedLocales[$locale]['regional'];

            return str_replace('-', '_', $regional);
        } else {
            return;
        }
    }

    /**
     * Returns the locale's script.
     *
     * @param $locale
     * @return mixed
     */
    public function getLocaleScript($locale)
    {
        // need to check if it exists
        if (isset($this->supportedLocales[$locale]['script'])) {
            $script = $this->supportedLocales[$locale]['script'];

            return str_replace('-', '_', $script);
        } else {
            return;
        }
    }

    /**
     * Return an array of all supported Locales but in the order the user
     * has specified in the config file. Useful for the language selector.
     *
     * @return array
     * @throws ActiveLocalesNotDefined
     */
    public function getLocalesOrder()
    {
        $locales = $this->getActiveLocales();
        $order = $this->config->get('localization.localesOrder');
        uksort($locales, function ($a, $b) use ($order) {
            $posA = array_search($a, $order);
            $posB = array_search($b, $order);

            return $posA - $posB;
        });

        return $locales;
    }

    /**
     * Returns an Url adapted to $locale.
     *
     * @param string|bool $locale
     * @param string|bool $url
     * @param array       $attributes
     * @param bool        $forceDefaultLocation
     * @return bool|null|string
     * @throws ActiveLocalesNotDefined
     * @throws SupportedLocalesNotDefined
     * @throws UnsupportedLocaleException
     */
    public function getLocalizedUrl($locale = null, $url = null, array $attributes = [], $forceDefaultLocation = false)
    {
        if ($locale === null) {
            $locale = $this->getCurrentLocale();
        }

        if (!$this->checkLocaleInSupportedLocales($locale)) {
            throw new UnsupportedLocaleException('Locale \'' . $locale . '\' is not in the list of supported locales.');
        }

        if (empty($attributes)) {
            $attributes = $this->extractAttributes($url, $locale);
        }

        if (empty($url)) {
            if (!empty($this->routeName)) {
                return $this->getUrlFromRouteNameTranslated($locale, $this->routeName, $attributes, $forceDefaultLocation);
            }

            $url = $this->request->fullUrl();
        } else {
            $url = $this->url->to($url);
        }

        if ($locale && $translatedRoute = $this->findTranslatedRouteByUrl($url, $attributes, $this->currentLocale)) {
            return $this->getUrlFromRouteNameTranslated($locale, $translatedRoute, $attributes, $forceDefaultLocation);
        }

        $basePath = $this->request->getBaseUrl();
        $parsedUrl = parse_url($url);
        $urlLocale = $this->getDefaultLocale();

        if (!$parsedUrl || empty($parsedUrl['path'])) {
            $path = $parsedUrl['path'] = '';
        } else {
            $parsedUrl['path'] = str_replace($basePath, '', '/' . ltrim($parsedUrl['path'], '/'));
            $path = $parsedUrl['path'];

            foreach ($this->getActiveLocales() as $localeCode => $lang) {
                $parsedUrl['path'] = preg_replace('%^/?' . $localeCode . '/%', '$1', $parsedUrl['path']);
                if ($parsedUrl['path'] !== $path) {
                    $urlLocale = $localeCode;
                    break;
                }

                $parsedUrl['path'] = preg_replace('%^/?' . $localeCode . '$%', '$1', $parsedUrl['path']);
                if ($parsedUrl['path'] !== $path) {
                    $urlLocale = $localeCode;
                    break;
                }
            }
        }

        $parsedUrl['path'] = ltrim($parsedUrl['path'], '/');

        if ($translatedRoute = $this->findTranslatedRouteByPath($parsedUrl['path'], $urlLocale)) {
            return $this->getUrlFromRouteNameTranslated($locale, $translatedRoute, $attributes, $forceDefaultLocation);
        }

        if (!empty($locale)) {
            if ($locale != $this->getDefaultLocale() || !$this->hideDefaultLocaleInUrl() || $forceDefaultLocation) {
                $parsedUrl['path'] = $locale . '/' . ltrim($parsedUrl['path'], '/');
            }
        }

        $parsedUrl['path'] = ltrim(ltrim($basePath, '/') . '/' . $parsedUrl['path'], '/');

        // make sure that the pass path is returned with a leading slash only if it come in with one
        if (starts_with($path, '/') === true) {
            $parsedUrl['path'] = '/' . $parsedUrl['path'];
        }

        $parsedUrl['path'] = rtrim($parsedUrl['path'], '/');

        $url = $this->unparseUrl($parsedUrl);

        if ($this->checkUrl($url)) {
            return $url;
        }

        return $this->createUrlFromUri($url);
    }

    /**
     * It returns an Url without locale (if it has it)
     * Convenience function wrapping getLocalizedUrl(false).
     *
     * @param string|null $url
     * @return bool|null|string
     * @throws ActiveLocalesNotDefined
     * @throws SupportedLocalesNotDefined
     * @throws UnsupportedLocaleException
     */
    public function getNonLocalizedUrl($url = null)
    {
        return $this->getLocalizedUrl(false, $url);
    }

    /**
     * Returns the translation key for a given path.
     *
     * @param string $path
     * @return string|bool
     */
    public function getRouteNameFromPath($path)
    {
        $attributes = $this->extractAttributes($path);

        $path = str_replace(url('/'), '', $path);
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $path = str_replace('/' . $this->currentLocale . '/', '', $path);
        $path = trim($path, '/');

        foreach ($this->translatedRoutes as $route) {
            if ($this->substituteAttributesInRoute($attributes, $this->translator->trans($route)) === $path) {
                return $route;
            }
        }

        return false;
    }

    /**
     * Returns supported languages language key.
     *
     * @return array
     */
    public function getSupportedLanguagesKeys()
    {
        return array_keys($this->supportedLocales);
    }

    /**
     * Return an array of all supported Locales.
     *
     * @return array
     * @throws SupportedLocalesNotDefined
     */
    public function getSupportedLocales()
    {
        if (!empty($this->supportedLocales)) {
            return $this->supportedLocales;
        }

        $locales = $this->config->get('localization.supportedLocales');
        if (empty($locales) || !is_array($locales)) {
            throw new SupportedLocalesNotDefined();
        }

        $this->supportedLocales = $locales;

        return $locales;
    }

    /**
     * Returns translated routes.
     *
     * @return array
     */
    protected function getTranslatedRoutes()
    {
        return $this->translatedRoutes;
    }

    /**
     * Returns an Url adapted to the route name and the locale given.
     *
     * @param string|bool $locale
     * @param string      $transKeyName
     * @param array       $attributes
     * @param bool        $forceDefaultLocation
     * @return bool|string
     * @throws SupportedLocalesNotDefined
     * @throws UnsupportedLocaleException
     */
    public function getUrlFromRouteNameTranslated($locale, $transKeyName, array $attributes = [], $forceDefaultLocation = false)
    {
        if (!$this->checkLocaleInSupportedLocales($locale)) {
            throw new UnsupportedLocaleException('Locale \'' . $locale . '\' is not in the list of supported locales.');
        }

        if (!is_string($locale)) {
            $locale = $this->getDefaultLocale();
        }

        $route = '';
        if ($forceDefaultLocation || !($locale === $this->defaultLocale && $this->hideDefaultLocaleInUrl())) {
            $route = '/' . $locale;
        }

        if (is_string($locale) && $this->translator->has($transKeyName, $locale)) {
            $translation = $this->translator->trans($transKeyName, [], $locale);
            $route .= '/' . $translation;
            $route = $this->substituteAttributesInRoute($attributes, $route);
        }

        if (empty($route)) {
            // this locale does not have any key for this route name
            return false;
        }

        return rtrim($this->createUrlFromUri($route));
    }

    /**
     * Returns the translation key for a given path.
     *
     * @return bool
     */
    public function hideDefaultLocaleInUrl()
    {
        return $this->config->get('localization.hideDefaultLocaleInUrl');
    }

    /**
     * Returns an Url adapted to $locale or current locale.
     *
     * @param string      $url
     * @param string|bool $locale
     * @return bool|null|string
     * @throws ActiveLocalesNotDefined
     * @throws SupportedLocalesNotDefined
     * @throws UnsupportedLocaleException
     */
    public function localizeUrl($url = null, $locale = null)
    {
        return $this->getLocalizedUrl($locale, $url);
    }

    /**
     * Normalize attributes gotten from request parameters.
     *
     * @param array $attributes
     * @return array
     */
    protected function normalizeAttributes(array $attributes)
    {
        if (array_key_exists('data', $attributes) && is_array($attributes['data']) && !count($attributes['data'])) {
            $attributes['data'] = null;

            return $attributes;
        }

        return $attributes;
    }

    /**
     * Set and return active locales.
     *
     * @param array $locales
     */
    public function setActiveLocales(array $locales)
    {
        $this->activeLocales = $locales;
    }

    /**
     * Sets the base url for the site.
     *
     * @param string $url
     */
    public function setBaseUrl($url)
    {
        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        $this->baseUrl = $url;
    }

    /**
     * Set and return current locale.
     *
     * @param string $locale
     * @return string
     * @throws ActiveLocalesNotDefined
     */
    public function setLocale($locale = null)
    {
        if (empty($locale) || !is_string($locale)) {
            // locale has not been passed through the function
            // try to get it from the first segment of the url
            $locale = $this->request->segment(1);

            // check for 2-segment locale (e.g.: us/en
            if ($this->request->segment(2) && $this->checkLocaleInActiveLocales($locale . '/' . $this->request->segment(2))) {
                $locale .= '/' . $this->request->segment(2);
            }
        }

        if (!empty($this->activeLocales[$locale])) {
            $this->currentLocale = $locale;
        } else {
            // locale passed is not valid
            $locale = null;
            if ($this->hideDefaultLocaleInUrl()) {
                // assume we are routing to a defaultLocale route
                $this->currentLocale = $this->defaultLocale;
            } else {
                // retrieve locale from the browser
                $this->currentLocale = $this->getCurrentLocale();
            }
        }

        $this->app->setLocale($this->currentLocale);

        // regional locale such as en_US, so formatLocalized works in Carbon
        if ($regional = $this->getCurrentLocaleRegional()) {
            setlocale(LC_TIME, $regional . '.UTF-8');
            setlocale(LC_MONETARY, $regional . '.UTF-8');
        }

        return $locale;
    }

    /**
     * Set current route name.
     *
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * Set and return supported locales.
     *
     * @param array $locales
     */
    public function setSupportedLocales(array $locales)
    {
        $this->supportedLocales = $locales;
    }

    /**
     * Change route attributes for the ones in the $attributes array.
     *
     * @param array  $attributes
     * @param string $route
     * @return mixed|null|string|string[]
     */
    protected function substituteAttributesInRoute(array $attributes, $route)
    {
        foreach ($attributes as $key => $value) {
            $route = str_replace('{' . $key . '}', $value, $route);
            $route = str_replace('{' . $key . '?}', $value, $route);
        }

        // delete empty optional arguments that are not in the $attributes array
        $route = preg_replace('/\/{[^)]+\?}/', '', $route);

        return $route;
    }

    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param string $routeName
     * @return string
     */
    public function transRoute($routeName)
    {
        if (!in_array($routeName, $this->translatedRoutes)) {
            $this->translatedRoutes[] = $routeName;
        }

        return $this->translator->trans($routeName);
    }

    /**
     * Build Url using array data from parsedUrl.
     *
     * @param array|bool $parsedUrl
     * @return string
     */
    protected function unparseUrl($parsedUrl)
    {
        if (empty($parsedUrl)) {
            return '';
        }

        $url = '';
        $url .= isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $url .= isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $url .= isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';

        $user = isset($parsedUrl['user']) ? $parsedUrl['user'] : '';
        $pass = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass'] : '';

        $url .= $user . (($user || $pass) ? "$pass@" : '');

        if (!empty($url)) {
            $url .= isset($parsedUrl['path']) ? '/' . ltrim($parsedUrl['path'], '/') : '';
        } else {
            $url .= isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        }

        $url .= isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $url .= isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return $url;
    }

    /**
     * Returns the translation key for a given path.
     *
     * @return bool
     */
    protected function useAcceptLanguageHeader()
    {
        return $this->config->get('localization.useAcceptLanguageHeader');
    }
}
