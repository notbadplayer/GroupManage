<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
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
        $userId = Auth::id();

        return [
            'name' => 'required|max:50',
            'surname' => 'required|max:100',
            'email' => [
                'nullable',
                Rule::unique('users')->ignore($userId),
                'email'
            ],
            'phone' => 'nullable'


        ];
    }

    public function messages()
    {
        return [
                'email.unique' => 'Podany adres email jest zajęty',
                'name.max' =>' Maksymalna ilość znaków to: :max',
                'name.required' =>' Imię jest wymagane',
                'surname.max' =>' Maksymalna ilość znaków to: :max'
        ];
    }

}
