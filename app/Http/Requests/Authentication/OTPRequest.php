<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OTPRequest extends FormRequest
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
        return [
            'otp' => ['required'],
            'hashed' => ['required', 'array'],
            'hashed.otp' => ['required'],
            'hashed.t' => ['required'],
            'hashed.email' => ['required', 'email', 'exists:users,email']
        ];
    }

//    protected function failedValidation(Validator $validator)
//    {
//        throw new HttpResponseException(
//            response()->failed("Invalid Data", $validator->errors(), ResponseAlias::HTTP_BAD_REQUEST)
//        );
//    }
}
