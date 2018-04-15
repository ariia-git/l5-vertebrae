<?php namespace App\Entities\Country;

use App\Entities\AbstractEntity;
use App\Entities\Currency\Currency;
use App\Entities\Locale\Locale;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Country extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'currency_id',
        'iso_code',
        'name'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function locales()
    {
        return $this->hasMany(Locale::class);
    }

    /**
     * @return Currency|null
     */
    public function getCurrency()
    {
        return $this->getAttribute('currency');
    }

    /**
     * @return string
     */
    public function getIsoCode()
    {
        return $this->getAttribute('iso_code');
    }

    /**
     * @return Locale[]|Collection
     */
    public function getLocales()
    {
        return $this->getAttribute('locales');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }
}
