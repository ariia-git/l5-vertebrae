<?php namespace App\Http\Requests\Locale;

use App\Http\Requests\AbstractFormRequest;

class CreateLocaleRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'country_id' => 'required|integer',
            'language_id' => 'required|integer',
            'code' => 'required|unique:locales'
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
