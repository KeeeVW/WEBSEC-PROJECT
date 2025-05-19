@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Admin - Manage All Customer Credits</h2>

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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Credit Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>${{ number_format($customer->credit_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('credits.add_form', $customer->id) }}" class="btn btn-sm btn-success">Add Credit</a>
                                    <a href="{{ route('users_edit', $customer->id) }}" class="btn btn-sm btn-primary">Edit User</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No customers found.</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('users') }}" class="btn btn-primary">Back to Users</a>
    </div>
</div>
@endsection 