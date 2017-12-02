<?php namespace App\Http\Requests\Country;

use App\Http\Requests\AbstractFormRequest;

class UpdateCountryRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'iso_code' => 'required|size:2|unique:countries,iso_code,' . $this->route('country'),
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
