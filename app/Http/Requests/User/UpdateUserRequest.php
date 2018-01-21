<?php namespace App\Http\Requests\User;

use App\Http\Requests\AbstractFormRequest;

class UpdateUserRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            //
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
