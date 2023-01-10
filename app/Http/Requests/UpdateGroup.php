<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroup extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'description' => 'nullable|max:150',
        ];
    }

    public function messages()
    {
        return [
                'name.max' =>' Maksymalna ilość znaków to: :max',
                'name.required' =>' Nazwa jest wymagana',
                'description.max' =>' Maksymalna ilość znaków to: :max',
        ];
    }

}
