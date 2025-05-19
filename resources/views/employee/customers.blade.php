@extends('layouts.master')

@section('title', 'Manage Customers')
@section('content')
<div class="container">
    <div class="row mt-2">
        <div class="col col-8">
            <h1>Customer Management</h1>
        </div>
        <div class="col col-4">
            <a href="{{ route('employee.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
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

    <form class="mb-4">
        <div class="row">
            <div class="col-sm-4">
                <input name="keywords" type="text" class="form-control" placeholder="Search by name" value="{{ request()->keywords }}" />
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Search</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
            </div>
        </div>
    </form>

    <div class="card mt-2">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Customer Accounts</h5>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Credit Balance</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
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
                                    @if(auth()->user()->managesCustomer($customer->id))
                                        <span class="badge bg-info">My Customer</span>
                                    @else
                                        <a href="{{ route('credits.add_form', $customer->id) }}" class="btn btn-sm btn-success">Add Credit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No customers found in the system.</p>
            @endif
        </div>
    </div>
</div>
@endsection 