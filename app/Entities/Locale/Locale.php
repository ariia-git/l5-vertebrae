<?php namespace App\Entities\Locale;

use App\Entities\AbstractEntity;
use App\Entities\Country\Country;
use App\Entities\Language\Language;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locale extends AbstractEntity
{
    use SoftDeletes;

    protected $table = \CreateLocalesTable::TABLENAME;

    protected $fillable = [
        'country_id',
        'language_id',
        'code',
        'currency_symbol_first',
        'decimal_mark',
        'thousands_separator',
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

    /**
     * Pull locales listed in database for localization use.
     *
     * @return array
     */
    public function getLocalesForConfig()
    {
        // required to run migrations
        $defaultLocale = [
            'en' => [
                'name' => 'English',
                'regional' => 'en',
                'script' => 'Latn',
                'active' => true
            ]
        ];

        if (\Schema::hasTable($this->table)) {
            if ($dbLocales = $this->with(['country', 'language'])->get()) {
                $locales = [];
                foreach ($dbLocales as $locale) {
                    $generatedLocale = [
                        'name' => $locale->language->name . '(' . $locale->country->iso_code . ')',
                        'regional' => $locale->language->iso_code . '_' . $locale->country->iso_code,
                        'script' => $locale->language->script
                    ];

                    $locales['supported'][$locale->code] = $generatedLocale;

                    if ($locale->active) {
                        $locales['active'][$locale->code] = $generatedLocale;
                    }
                }
            }
        }

        if (empty($locales['supported'])) {
            $locales['supported'] = $defaultLocale;
        }

        if (empty($locales['active'])) {
            $locales['active'] = $defaultLocale;
        }

        return $locales;
    }
}
