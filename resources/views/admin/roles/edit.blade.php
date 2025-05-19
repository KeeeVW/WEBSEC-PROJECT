@extends('layouts.master')
@section('title', 'Edit Role')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Edit Role: {{ $role->name }}</h2>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    @error('permissions')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" 
                                    {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                                        {{ $permission->display_name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 