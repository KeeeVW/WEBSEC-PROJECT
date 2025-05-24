@extends('layouts.master')
@section('title', 'Role Management')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Role Management</h5>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">Create New Role</a>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ ucfirst($role->name) }}</td>
                                        <td>
                                            @foreach($role->permissions as $permission)
                                                <span class="badge bg-info me-1">{{ $permission->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                                                @if($role->name !== 'admin' && $role->name !== 'customer')
                                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 