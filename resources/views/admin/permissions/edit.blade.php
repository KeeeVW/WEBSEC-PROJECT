@extends('layouts.master')
@section('title', 'Edit Permission')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Edit Permission: {{ $permission->name }}</h2>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                    <small class="form-text text-muted">Use snake_case format (e.g., manage_users)</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="display_name" class="form-label">Display Name</label>
                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name', $permission->display_name) }}" required>
                    <small class="form-text text-muted">Human-readable name (e.g., Manage Users)</small>
                    @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 