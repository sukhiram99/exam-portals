{{-- resources/views/exam/pay.blade.php --}}
@extends('layouts.app')

@section('title', 'Complete Payment - ₹500')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm text-center">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="bi bi-credit-card"></i> Complete Payment</h4>
                    </div>
                    <div class="card-body py-5">
                        <h5>Applicant: <strong>{{ $form->full_name }}</strong></h5>
                        <p class="text-muted">Course: {{ $form->course }}</p>
                        <hr>
                        <h2 class="text-success mb-4">Amount: ₹500</h2>

                        <button id="payNowBtn" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-wallet2"></i> Pay Now with Razorpay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('payNowBtn').onclick = function() {
            fetch("/admin/api/create-order", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        form_id: {{ $form->id }}
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        alert("Error: " + data.message);
                        return;
                    }

                    new Razorpay({
                        key: data.key,
                        amount: data.amount,
                        currency: "INR",
                        name: "Exam Portal",
                        order_id: data.order_id,
                        handler: function(response) {
                            fetch("/api/verify-payment", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({
                                        form_id: {{ $form->id }},
                                        razorpay_order_id: response.razorpay_order_id,
                                        razorpay_payment_id: response.razorpay_payment_id,
                                        razorpay_signature: response.razorpay_signature
                                    })
                                })
                                .then(r => r.json())
                                .then(res => {
                                    if (res.success) {
                                        alert("Payment Successful!");
                                        window.location.href = "{{ route('admin.exam.forms') }}";
                                    } else {
                                        alert("Payment Failed: " + res.message);
                                    }
                                });
                        },
                        prefill: {
                            name: "{{ $form->full_name }}",
                            email: "{{ $form->email }}"
                        }
                    }).open();
                });
        }
    </script>
@endsection
