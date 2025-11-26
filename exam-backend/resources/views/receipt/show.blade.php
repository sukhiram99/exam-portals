{{-- resources/views/receipt/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h4><i class="bi bi-check-circle-fill"></i> Payment Successful!</h4>
                    </div>
                    <div class="card-body text-center">
                        <iframe src="{{ Storage::url($payment->pdf_path) }}" width="100%" height="700"
                            class="border rounded"></iframe>

                        <div class="mt-4">
                            <a href="{{ route('receipt.download', $payment->id) }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-download"></i> Download PDF Receipt
                            </a>
                            <a href="{{ route('exam.forms') }}" class="btn btn-secondary btn-lg ms-2">
                                Back to Forms
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
