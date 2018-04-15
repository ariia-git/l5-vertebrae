<?php namespace App\Services\Api\Currency;

use App\Services\Api\AbstractApiService;

class OpenExchangeRatesApiService extends AbstractApiService
{
    public function __construct()
    {
        $this->baseUri = 'https://openexchangerates.org/api/';

        $this->headers([
            'Authorization' => 'Token ' . env('OPEN_EXCHANGE_RATES_APP_ID')
        ]);

        parent::__construct();
    }
}
