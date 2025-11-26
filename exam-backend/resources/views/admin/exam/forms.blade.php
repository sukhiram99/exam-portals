{{-- resources/views/exam/forms.blade.php --}}
@extends('layouts.app')

@section('title', 'My Exam Forms')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-file-text"></i> My Exam Forms
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="text-end mb-3">
                            <a href="{{ route('admin.exam.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Submit New Form
                            </a>
                        </div>

                        @forelse($forms as $form)
                            <div class="card mb-3 border {{ $form->is_paid ? 'border-success' : 'border-warning' }}">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ $form->full_name }}</h5>
                                        <p class="mb-1 text-muted">
                                            <strong>Course:</strong> {{ $form->course }} |
                                            <strong>Email:</strong> {{ $form->email }}
                                        </p>
                                        <small class="text-muted">
                                            Submitted: {{ $form->created_at->format('d M Y, h:i A') }}
                                        </small>
                                    </div>

                                    <div class="text-end">
                                        @if ($form->is_paid)
                                            <span class="badge bg-success fs-6 mb-2">PAID</span><br>
                                            <a href="{{ route('admin.receipt.show', $form->payment->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-file-earmark-check"></i> View Receipt
                                            </a>
                                        @else
                                            <span class="badge bg-warning text-dark fs-6 mb-2">Payment Pending</span><br>
                                            <a href="{{ route('admin.exam.pay', $form->id) }}" class="btn btn-danger">
                                                <i class="bi bi-currency-rupee"></i> Pay ₹500
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="text-muted">No exam forms submitted yet.</p>
                                <a href="{{ route('exam.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Submit Your First Form
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
