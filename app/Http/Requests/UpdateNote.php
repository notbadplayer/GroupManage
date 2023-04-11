<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateNote extends FormRequest
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
            'name' => 'required|max:100',
            'table' =>'sometimes|max:10',
            'transpose' =>'sometimes|max:10',
            'category' => 'sometimes',
            'upload' => 'sometimes|max:10240|mimes:pdf,jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return [
                'name.max' =>' Maksymalna ilość znaków to: :max',
                'name.required' =>'Nazwa jest wymagana',
                'table.max' =>' Maksymalna ilość znaków to: :max',
                'transpose.max' =>' Maksymalna ilość znaków to: :max',
                'upload.required' =>'Musisz dołączyć plik',
                'upload.max' => 'Przekroczony maksymalny dozwolony rozmiar pliku',
                'upload.mimes' => 'Niedozwolony format pliku.',
        ];
    }
}
