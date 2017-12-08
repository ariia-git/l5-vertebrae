<?php namespace App\Services\Entities\Currency;

use App\Entities\Currency\CurrencyRepository;
use App\Services\Entities\AbstractService;
use App\Services\Entities\Locale\LocaleService;
use App\Services\Localization\LocalizationService;

class CurrencyService extends AbstractService
{
    /**
     * @var float|string
     */
    protected $amount;

    /**
     * @var array
     */
    protected $currencyInfo;

    /**
     * @var string
     */
    protected $localeCode;

    /**
     * @var LocalizationService
     */
    protected $localizationService;

    /**
     * @var bool
     */
    protected $useCurrencyCode = false;

    /**
     * CurrencyService constructor.
     *
     * @param CurrencyRepository  $repo
     * @param LocalizationService $localizationService
     * @throws \App\Exceptions\ActiveLocalesNotDefined
     */
    public function __construct(CurrencyRepository $repo, LocalizationService $localizationService)
    {
        $this->repo = $repo;
        $this->localizationService = $localizationService;

        $this->localeCode = $localizationService->getCurrentLocale();

        $this->getCurrencyInfo();
    }

    /**
     * Convert currency to a different rate.
     *
     * @param float $fromRate
     * @param float $toRate
     * @return $this
     */
    public function convert($fromRate = 1.00, $toRate = 1.00)
    {
        $amount = ($this->amount / $fromRate) * $toRate;

        $this->setAmount($amount);

        return $this;
    }

    /**
     * Format the currency string.
     *
     * @return $this
     * @throws \App\Exceptions\ActiveLocalesNotDefined
     */
    public function format()
    {
        $amount = $this->amount;

        if ($this->isInternational()) {
            $this->useCurrencyCode = true;

            $defaultCurrency = $this->repo->findFirstBy('iso_code', config('app.currency'));

            $this->convert($defaultCurrency->exchange_rate, $this->currencyInfo['exchange_rate']);

            $this->setAmount($amount);
        }

        $amount = number_format($amount, $this->currencyInfo['decimal_precision'], $this->currencyInfo['decimal_mark'], $this->currencyInfo['thousands_separator']);

        if ($this->useCurrencyCode) {
            $amount = $this->currencyInfo['iso_code'] . ' ' . $amount;
        } else {
            if ($this->currencyInfo['symbol_first']) {
                $amount = $this->currencyInfo['symbol'] . $amount;
            } else {
                $amount = $amount . $this->currencyInfo['symbol'];
            }
        }

        $this->setAmount($amount);

        return $this;
    }

    /**
     * Round currency to the nearest value.
     *
     * @param bool|float  $value
     * @param bool|string $direction
     * @return $this
     */
    public function round($value = false, $direction = false)
    {
        $amount = $this->amount;

        if ($value) {
            if ($direction && $direction == 'up') {
                $amount = ceil($amount / $value) * $value;
            } elseif ($direction && $direction == 'down') {
                $amount = floor($amount / $value) * $value;
            } else {
                $amount = round($amount / $value) * $value;
            }
        }

        $this->setAmount($amount);

        return $this;
    }

    /**
     * Set the amount to be converted.
     *
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Set the locale for conversion.
     *
     * @param string $localeCode
     * @return $this
     * @throws \App\Exceptions\SupportedLocalesNotDefined
     */
    public function setLocale($localeCode)
    {
        if ($this->localizationService->checkLocaleInSupportedLocales($localeCode)) {
            $this->localeCode = $localeCode;
        }

        $this->getCurrencyInfo();

        return $this;
    }

    /**
     * Get the final currency string.
     *
     * @return string
     */
    public function get()
    {
        return $this->amount;
    }

    /**
     * Get all data for the current currency.
     *
     * @return $this
     */
    private function getCurrencyInfo()
    {
        $locale = app(LocaleService::class)->with(['country.currency'])->findFirstBy('code', $this->localeCode);

        $country = $locale->country;

        if (is_null($country->currency)) {
            $currency = $this->repo->findFirstBy('iso_code', config('app.currency'));
        } else {
            $currency = $country->currency;
        }

        $this->currencyInfo = [
            'iso_code' => $currency->iso_code,
            'symbol' => $currency->symbol,
            'symbol_first' => $locale->currency_symbol_first,
            'decimal_mark' => $locale->decimal_mark,
            'decimal_precision' => $currency->decimal_precision,
            'thousands_separator' => $locale->thousands_separator,
            'exchange_rate' => $currency->exchange_rate
        ];

        return $this;
    }

    /**
     * Check to see if the conversion locale matches the current.
     *
     * @return bool
     * @throws \App\Exceptions\ActiveLocalesNotDefined
     */
    private function isInternational()
    {
        $localeService = app(LocaleService::class);
        $locale = $localeService->with(['country'])->findFirstBy('code', $this->localeCode);
        $currentLocale = $localeService->with(['country'])->findFirstBy('code', $this->localizationService->getCurrentLocale());

        $localeCountry = $locale->country;
        $currencyCountry = $currentLocale->country;

        return $localeCountry->iso_code != $currencyCountry->iso_code;
    }
}
