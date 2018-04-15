<?php namespace App\Console\Commands;

use App\Entities\Currency\Currency;
use App\Services\Api\Currency\OpenExchangeRatesApiService;
use App\Services\Entities\Currency\CurrencyService;
use Illuminate\Console\Command;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:update-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency exchange rates';

    /**
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * @var OpenExchangeRatesApiService
     */
    protected $exchangeRateApiService;

    public function __construct(CurrencyService $currencyService, OpenExchangeRatesApiService $exchangeRateApiService)
    {
        $this->currencyService = $currencyService;
        $this->exchangeRateApiService = $exchangeRateApiService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currencies = $this->currencyService->all();
        $exchangeRates = $this->exchangeRateApiService->get('latest.json');

        $currencies->each(function (Currency $currency) use ($exchangeRates) {
            if (array_key_exists($currency->getIsoCode(), $exchangeRates['rates'])) {
                $currency->setAttribute('exchange_rate', $exchangeRates['rates'][$currency->getIsoCode()]);
                $currency->save();
            } else {
                $this->warn('Exchange rate for ' . $currency->getName() . ' does not exist in OXR API');
            }
        });

        $this->info('Exchange Rates have been updated');
    }
}
