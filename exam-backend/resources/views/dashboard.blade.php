{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <style>
        .dashboard-card {
            border-radius: 12px;
            transition: .3s;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, .15);
        }
    </style>

    <div class="mb-4">
        <div class="p-4 bg-light border rounded shadow-sm">
            <h4 class="mb-1">Welcome back, <strong>{{ auth()->user()->name }}</strong> 👋</h4>
            <p class="text-muted mb-0">
                Roles: <strong>{{ auth()->user()->roles->pluck('name')->implode(', ') }}</strong>
            </p>
        </div>
    </div>

    {{-- ADMIN DASHBOARD --}}
    @if (auth()->user()->is_admin)
        <h4 class="fw-bold mb-3">Admin Overview</h4>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card dashboard-card text-white bg-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Users</h6>
                            <h2>{{ \App\Models\User::count() }}</h2>
                        </div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card text-white bg-success">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Roles</h6>
                            <h2>{{ \App\Models\Role::count() }}</h2>
                        </div>
                        <i class="bi bi-shield-lock fs-1"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card text-white bg-warning">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Permissions</h6>
                            <h2>{{ \App\Models\Permission::count() }}</h2>
                        </div>
                        <i class="bi bi-key fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- USER DASHBOARD --}}
    @if (!auth()->user()->is_admin)
        <h4 class="fw-bold mt-4 mb-3">Your Dashboard</h4>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card dashboard-card border-info">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-info mb-1">Your Role(s)</h6>
                            <h3>{{ auth()->user()->roles->pluck('name')->implode(', ') }}</h3>
                        </div>
                        <i class="bi bi-person-badge text-info fs-1"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card dashboard-card border-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-primary mb-1">Account Created</h6>
                            <h3>{{ auth()->user()->created_at->format('M d, Y') }}</h3>
                        </div>
                        <i class="bi bi-calendar-check text-primary fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
