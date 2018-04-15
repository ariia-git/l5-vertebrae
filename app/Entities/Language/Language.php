<?php namespace App\Entities\Language;

use App\Entities\AbstractEntity;
use App\Entities\Locale\Locale;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Language extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'iso_code',
        'name',
        'script'
    ];

    public function locales()
    {
        return $this->hasMany(Locale::class);
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

    /**
     * @return string
     */
    public function getScript()
    {
        return $this->getAttribute('script');
    }
}
