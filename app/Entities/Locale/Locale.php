<?php namespace App\Entities\Locale;

use App\Entities\AbstractEntity;
use App\Entities\Country\Country;
use App\Entities\Language\Language;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locale extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'country_id',
        'language_id',
        'code',
        'active'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
