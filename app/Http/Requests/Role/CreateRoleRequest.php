<?php namespace App\Http\Requests\Role;

use App\Http\Requests\AbstractFormRequest;

class CreateRoleRequest extends AbstractFormRequest
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
