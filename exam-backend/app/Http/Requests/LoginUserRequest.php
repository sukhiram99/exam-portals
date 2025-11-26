<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginUserRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            // 'phone' => [ // Using array syntax for multiple rules for readability
            //     'required',
            //     'regex:/^[6-9]\d{9}$/',
            //     'not_in:6666666666,7777777777,8888888888,9999999999',
            //     'exists:users,phone',
            // ],
        ];
    }

     public function messages(): array
    {
        return [
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address format.',
            'password.required' => 'A password is required.',
            'phone.required' => 'A phone number is required.',
            'phone.regex' => 'Please provide a valid 10-digit phone number starting with 6, 7, 8, or 9.',
            'phone.not_in' => 'This phone number cannot be used.',
            'phone.unique' => 'This phone number has already been registered.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
