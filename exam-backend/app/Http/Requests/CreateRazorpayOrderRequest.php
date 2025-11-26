<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRazorpayOrderRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'form_id' => 'required|exists:exam_forms,id',
            'amount'  => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'form_id.required' => 'Exam form ID is required.',
            'form_id.exists'   => 'Exam form not found.',
            'amount.required'  => 'Amount is required.',
            'amount.numeric'   => 'Amount must be a number.',
            'amount.min'       => 'Amount must be at least 1 INR.',
        ];
    }
}
