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
}
