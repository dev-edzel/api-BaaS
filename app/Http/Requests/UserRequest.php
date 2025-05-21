<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
                'phone_number' => [
                    'required',
                    'string',
                    'regex:/^\+?[0-9]{7,15}$/'
                ],
                'kyc_status' => ['nullable', 'in:PENDING,APPROVED,REJECTED'],
                'two_factor_enabled' => ['nullable', 'boolean'],
                'is_verified' => ['nullable', 'boolean'],
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->failed("Invalid Data", $validator->errors(), ResponseAlias::HTTP_BAD_REQUEST)
        );
    }
}
