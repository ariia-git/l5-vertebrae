<?php namespace App\Entities\Country;

use App\Entities\AbstractEntity;
use App\Entities\Locale\Locale;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'iso_code',
        'name'
    ];

    public function locales()
    {
        return $this->hasMany(Locale::class);
    }
}
