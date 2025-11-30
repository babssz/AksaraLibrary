<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users',
                'regex:/@aksara\.ac\.id$/i'
            ],
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'required|string|max:20|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Hanya email dengan domain @aksara.ac.id yang diperbolehkan untuk mendaftar.',
        ];
    }
}