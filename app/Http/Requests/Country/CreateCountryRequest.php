<?php namespace App\Http\Requests\Country;

use App\Http\Requests\AbstractFormRequest;

class CreateCountryRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'currency_id' => 'required|integer',
            'iso_code' => 'required|size:2|unique:countries',
            'name' => 'required'
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
