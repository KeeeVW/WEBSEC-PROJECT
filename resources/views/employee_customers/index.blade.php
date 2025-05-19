@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Customer Management</h2>

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
            <h4>All Customers</h4>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Credit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    @foreach($customer->roles as $role)
                                        @if($role->name == 'customer')
                                            <span class="badge bg-success">Customer</span>
                                        @elseif($role->name == 'employee')
                                            <span class="badge bg-warning text-dark">Employee</span>
                                        @elseif($role->name == 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>${{ number_format($customer->getCreditAmount(), 2) }}</td>
                                <td>
                                    <a href="{{ route('profile', $customer->id) }}" class="btn btn-sm btn-info">View Profile</a>
                                    <a href="{{ route('credits.add_form', $customer->id) }}" class="btn btn-sm btn-success">Add Credit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No customers available.</p>
            @endif
        </div>
    </div>
</div>
@endsection 