<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitExamFormRequest extends FormRequest
{
    public function authorize()
    {
        // Allow only authenticated users
        return auth()->check();
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255|exists:users,email',
            'course'    => 'required|string|in:btech,bsc',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required.',
            'email.required'     => 'Email address is required.',
            'email.email'        => 'Enter a valid email.',
            'email.exists'       => 'Email not exists in our records.',
            'course.required'    => 'Course is required.',
        ];
    }
}
