<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()) {
            'POST' => [
                'username' => ['required', 'string', 'unique:users,username'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => [
                    'nullable',
                    Password::min(10)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
                'userInfo.first_name' => ['required', 'string'],
                'userInfo.middle_name' => ['nullable', 'string'],
                'userInfo.last_name' => ['required', 'string'],
                'userInfo.avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            ],
            'PUT|PATCH' => [
                'username' => ['nullable', 'string'],
                'email' => ['nullable', 'email'],
                'userInfo.first_name' => ['nullable', 'string'],
                'userInfo.middle_name' => ['nullable', 'string'],
                'userInfo.last_name' => ['nullable', 'string'],
                'userInfo.avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            ],
            default => [
                'search' => 'nullable',
                'per_page' => 'nullable',
            ]
        };
    }
}
