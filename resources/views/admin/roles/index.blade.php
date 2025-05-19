@extends('layouts.master')
@section('title', 'Roles Management')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-10">
            <h2>Roles Management</h2>
        </div>
        <div class="col-md-2 text-end">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Create New Role</a>
        </div>
    </div>
    
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
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th width="200px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-success">{{ $permission->display_name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $role->users->count() }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 