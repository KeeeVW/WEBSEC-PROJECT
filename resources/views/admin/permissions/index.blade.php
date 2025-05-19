@extends('layouts.master')
@section('title', 'Permissions Management')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-10">
            <h2>Permissions Management</h2>
        </div>
        <div class="col-md-2 text-end">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create New Permission</a>
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
                        <th>Display Name</th>
                        <th>Roles</th>
                        <th width="200px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->display_name }}</td>
                            <td>
                                @foreach($permission->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this permission?')">Delete</button>
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