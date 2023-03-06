<?php

namespace App\Http\Requests;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
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
            'current_password' => ['required', new MatchOldPassword],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ];

    }

    public function messages()
    {
        return [
            'current_password.required' => 'Podaj obecne hasło',
            'password.required' => 'Podaj nowe hasło',
            'password.min' => 'Minimalna ilość znaków to :min',
            'password.confirmed' => 'Podane hasła nie są ze sobą zgodne.',
        ];
    }
}
