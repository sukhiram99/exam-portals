<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'form_id'              => 'required|exists:exam_forms,id',
            'razorpay_payment_id'  => 'required|string',
            'razorpay_order_id'    => 'required|string',
            'razorpay_signature'   => 'required|string',
            'amount'               => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'form_id.required'             => 'Exam form ID is required.',
            'form_id.exists'               => 'Exam form not found.',
            'razorpay_payment_id.required' => 'Payment ID is required.',
            'razorpay_order_id.required'   => 'Order ID is required.',
            'razorpay_signature.required'  => 'Signature is required.',
            'amount.required'              => 'Amount is required.',
            'amount.numeric'               => 'Amount must be numeric.',
            'amount.min'                   => 'Amount must be at least 1 INR.',
        ];
    }
}
