<?php namespace App\Http\Requests\Language;

use App\Http\Requests\AbstractFormRequest;

class CreateLanguageRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'iso_code' => 'required|size:2|unique:languages',
            'name' => 'required',
            'script' => 'required|size:4'
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
