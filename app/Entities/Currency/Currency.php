<?php namespace App\Entities\Currency;

use App\Entities\AbstractEntity;
use App\Entities\Country\Country;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'iso_code',
        'name',
        'symbol',
        'decimal_precision',
        'exchange_rate'
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    /**
     * @return Country|null
     */
    public function getCountry()
    {
        return $this->getAttribute('country');
    }

    /**
     * @return int
     */
    public function getDecimalPrecision()
    {
        return $this->getAttribute('decimal_precision');
    }

    /**
     * @return float
     */
    public function getExchangeRate()
    {
        return $this->getAttribute('exchange_rate');
    }

    /**
     * @return string
     */
    public function getIsoCode()
    {
        return $this->getAttribute('iso_code');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this - $this->getAttribute('name');
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->getAttribute('symbol');
    }
}
