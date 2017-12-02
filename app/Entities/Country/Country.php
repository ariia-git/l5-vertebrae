<?php namespace App\Entities\Country;

use App\Entities\AbstractEntity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'iso_code',
        'name'
    ];
}
