@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Manage Customer Credits</h2>

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

    <div class="mb-3">
        <a href="{{ route('employee_customers.index') }}" class="btn btn-primary">Manage Customers</a>
    </div>

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
                            <th>Current Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>${{ number_format($customer->getCreditAmount(), 2) }}</td>
                                <td>
                                    <a href="{{ route('credits.add_form', $customer->id) }}" class="btn btn-sm btn-success">Add Credit</a>
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
</div>
@endsection 