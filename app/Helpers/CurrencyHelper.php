<?php

use App\Services\Entities\Currency\CurrencyService;

if (!function_exists('currency')) {
    /**
     * Converts integer/float to readable currency based on locale.
     *
     * @param int}float $amount
     * @param bool|string $localeCode
     * @param bool|float  $roundValue
     * @param bool|string $roundDirection
     * @return string
     * @throws \App\Exceptions\ActiveLocalesNotDefined
     */
    function currency($amount, $localeCode = false, $roundValue = false, $roundDirection = false)
    {
        $currency = app(CurrencyService::class);

        $currency->setAmount($amount);

        if ($localeCode) {
            $currency->setLocale($localeCode);
        }

        if ($roundValue || $roundDirection) {
            $currency->round($roundValue, $roundDirection);
        }

        $currency->format();

        return $currency->get();
    }
}
