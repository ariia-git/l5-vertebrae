<?php namespace App\Http\Requests\Currency;

use App\Http\Requests\AbstractFormRequest;

class CreateCurrencyRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'iso_code' => 'required|size:3|unique:currencies',
            'name' => 'required',
            'symbol' => 'required',
            'decimal_precision' => 'required',
            'exchange_rate' => 'required'
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
