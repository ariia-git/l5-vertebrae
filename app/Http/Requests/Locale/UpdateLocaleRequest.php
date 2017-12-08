<?php namespace App\Http\Requests\Locale;

use App\Http\Requests\AbstractFormRequest;

class UpdateLocaleRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'country_id' => 'required|integer',
            'language_id' => 'required|integer',
            'code' => 'required|unique:locales,code,' . $this->route('locale'),
            'currency_symbol_first' => 'required',
            'decimal_mark' => 'required',
            'thousands_separator' => 'required'
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
