<?php namespace App\Http\Requests\Language;

use App\Http\Requests\AbstractFormRequest;

class UpdateLanguageRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'iso_code' => 'required|size:2|unique:languages,iso_code,' . $this->route('language'),
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
