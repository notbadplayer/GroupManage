<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublication extends FormRequest
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
            'name' => 'required|max:150',
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
                'name.required' => 'Nazwa wpisu jest wymagana',
                'name.max' =>' Maksymalna ilość znaków to: :max',
                'content.required' => 'Treść wpisu jest wymagana',
        ];
    }
}
