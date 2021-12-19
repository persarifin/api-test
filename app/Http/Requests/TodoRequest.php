<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TodoRequest extends FormRequest
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
            'name' => $this->method() == 'PUT' || $this->method() == 'PUT' ? ['required','string','max:255', Rule::unique('todos')->ignore($this->route('payment'))] : 'required|string|max:255|unique:todos',
            'bonus' => 'required|numeric|gt:0',
            'wages' => 'required|array',
            'wages.*.user_id' => 'required|integer',
            'wages.*.percentage' => 'required|numeric',
            'wages.*.wage_price' => 'required|numeric',
        ];
    }
}
