{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="alert alert-info">
        Welcome, <strong>{{ auth()->user()->name }}</strong>!
        Your roles: {{ auth()->user()->roles->pluck('name')->implode(', ') }}
    </div>

    @if (auth()->user()->is_admin)
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Total User</h5>
                        <h3>{{ \App\Models\User::count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Total Roles</h5>
                        <h3>{{ \App\Models\Role::count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Total Permission</h5>
                        <h3>{{ \App\Models\Permission::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
