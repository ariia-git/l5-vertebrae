<?php namespace App\Http\Requests\Permission;

use App\Http\Requests\AbstractFormRequest;

class UpdatePermissionRequest extends AbstractFormRequest
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
