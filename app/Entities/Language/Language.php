<?php namespace App\Entities\Language;

use App\Entities\AbstractEntity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'iso_code',
        'name',
        'script'
    ];
}
