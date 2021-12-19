<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => $this->method() == 'put' || $this->method() == 'patch'? Rule::unique('role')->ignore($this->route('roles')) : 'required:unique:roles',
            'permission_ids' => 'required|array'
        ];
    }
}
