<?php namespace App\Services\Localization;

use Illuminate\Http\Request;

class LanguageNegotiator
{
    /**
     * @var array
     */
    private $activeLanguages;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $useIntl = false;

    /**
     * @param string  $defaultLocale
     * @param array   $activeLanguages
     * @param Request $request
     */
    public function __construct($defaultLocale, $activeLanguages, Request $request)
    {
        $this->defaultLocale = $defaultLocale;

        if (class_exists('Locale')) {
            $this->useIntl = true;

            foreach ($activeLanguages as $key => $activeLanguage) {
                if (!isset($activeLanguage['lang'])) {
                    $activeLanguage['lang'] = \Locale::canonicalize($key);
                } else {
                    $activeLanguage['lang'] = \Locale::canonicalize($activeLanguage['lang']);
                }

                if (isset($activeLanguage['regional'])) {
                    $activeLanguage['regional'] = \Locale::canonicalize($activeLanguage['regional']);
                }

                $this->activeLanguages[$key] = $activeLanguage;
            }
        } else {
            $this->activeLanguages = $activeLanguages;
        }

        $this->request = $request;
    }

    /**
     * Negotiates language with the user's browser through the Accept-Language
     * HTTP header or the user's host address.  Language codes are generally in
     * the form "ll" for a language spoken in only one country, or "ll-CC" for a
     * language spoken in a particular country.  For example, U.S. English is
     * "en-US", while British English is "en-UK".  Portuguese as spoken in
     * Portugal is "pt-PT", while Brazilian Portuguese is "pt-BR".
     * This function is based on negotiateLanguage from Pear HTTP2
     * http://pear.php.net/package/HTTP2/
     * Quality factors in the Accept-Language: header are supported, e.g.:
     *      Accept-Language: en-UK;q=0.7, en-US;q=0.6, no, dk;q=0.8
     *
     * @return string The negotiated language result or app.locale.
     */
    public function negotiateLanguage()
    {
        $matches = $this->getMatchesFromAcceptedLanguages();
        foreach ($matches as $key => $q) {
            if (!empty($this->activeLanguages[$key])) {
                return $key;
            }

            if ($this->useIntl) {
                $key = \Locale::canonicalize($key);
            }

            // search for acceptable locale by 'regional' => 'af_ZA' or 'lang' => 'af-ZA' match
            foreach ($this->activeLanguages as $keyActive => $locale) {
                if ((isset($locale['regional']) && $locale['regional'] == $key) || (isset($locale['lang']) && $locale['lang'] == $key)) {
                    return $keyActive;
                }
            }
        }

        // any (i.e. "*") is acceptable, return the first supported format
        if (isset($matches['*'])) {
            reset($this->activeLanguages);

            return key($this->activeLanguages);
        }

        if ($this->useIntl && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $httpAcceptLanguage = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

            if (!empty($this->activeLanguages[$httpAcceptLanguage])) {
                return $httpAcceptLanguage;
            }
        }

        if ($this->request->server('REMOTE_HOST')) {
            $remoteHost = explode('.', $this->request->server('REMOTE_HOST'));
            $lang = strtolower(end($remoteHost));

            if (!empty($this->activeLanguages[$lang])) {
                return $lang;
            }
        }

        return $this->defaultLocale;
    }

    /**
     * Return all the accepted languages from the browser.
     *
     * @return array Matches from the header field Accept-Languages
     */
    private function getMatchesFromAcceptedLanguages()
    {
        $matches = [];
        if ($acceptLanguages = $this->request->header('Accept-Language')) {
            $acceptLanguages = explode(',', $acceptLanguages);

            $genericMatches = [];
            foreach ($acceptLanguages as $option) {
                $option = array_map('trim', explode(';', $option));
                $l = $option[0];

                if (isset($option[1])) {
                    $q = (float)str_replace('q=', '', $option[1]);
                } else {
                    $q = null;
                    // assign default low weight for generic values
                    if ($l == '*/*') {
                        $q = 0.01;
                    } elseif (substr($l, -1) == '*') {
                        $q = 0.02;
                    }
                }

                // Unweighted values, get high weight by their position in the list
                $q = isset($q) ? $q : 1000 - count($matches);
                $matches[$l] = $q;

                // if for some reason the Accept-Language header only sends language with country, we should make the
                // language without country an accepted option with a value less than it's parent
                $languageOption = explode('-', $l);
                array_pop($languageOption);

                while (!empty($languageOption)) {
                    // the new generic option needs to be slightly less important than it's base
                    $q -= 0.001;
                    $op = implode('-', $languageOption);

                    if (empty($genericMatches[$op]) || $genericMatches[$op] > $q) {
                        $genericMatches[$op] = $q;
                    }

                    array_pop($languageOption);
                }
            }

            $matches = array_merge($genericMatches, $matches);

            arsort($matches, SORT_NUMERIC);
        }

        return $matches;
    }
}
