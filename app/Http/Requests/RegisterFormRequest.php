<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|regex:/\w{2,}\s\w.+/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^a-zA-Z0-9]).{6,}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => __('auth.invalid_name'),
            'email' => __('auth.invalid_email'),
            'email.unique' => __('auth.unique_email'),
            'password' => __('auth.invalid_password'),
        ];
    }
}
