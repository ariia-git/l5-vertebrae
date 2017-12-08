<?php namespace App\Entities\Country;

use App\Entities\AbstractEntity;
use App\Entities\Currency\Currency;
use App\Entities\Locale\Locale;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
