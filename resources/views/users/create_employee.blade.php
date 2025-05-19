@extends('layouts.master')
@section('title', 'Create Employee Account')
@section('content')

<div class="container">
    <h2>Create Employee Account</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <h4>New Employee Information</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users_create_employee_post') }}">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small class="form-text text-muted">Password must be at least 8 characters long and include numbers, uppercase, lowercase, and special characters.</small>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
                    <label class="form-check-label" for="is_admin">Also grant admin privileges</label>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Employee Account</button>
                <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection 