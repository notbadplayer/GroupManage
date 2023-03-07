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
        return [
            'name' => 'required|max:50',
            'surname' => 'required|max:100',
            'email' => 'required|email|unique:users_chor,email,'.$this->User?->id ?? null,
            'phone' => 'nullable'


        ];
    }

    public function messages()
    {
        return [
                'email.unique' => 'Podany adres email jest zajęty',
                'email.required' => 'Adres mailowy jest wymagany',
                'name.max' =>' Maksymalna ilość znaków to: :max',
                'name.required' =>' Imię jest wymagane',
                'surname.max' =>' Maksymalna ilość znaków to: :max',
                'surname.required' =>' Nazwisko jest wymagane',
        ];
    }

}
