@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add Customer</h2>

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
        <a href="{{ route('employee_customers.index') }}" class="btn btn-primary">Back to Customers</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Select Customer</h4>
        </div>
        <div class="card-body">
            @if($availableCustomers->count() > 0)
                <form method="POST" action="{{ route('employee_customers.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="customer_id">Customer</label>
                        <select class="form-control" id="customer_id" name="customer_id" required>
                            <option value="">Select a customer</option>
                            @foreach($availableCustomers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Add Customer</button>
                    <a href="{{ route('employee_customers.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            @else
                <p>There are no available customers to add.</p>
                <a href="{{ route('employee_customers.index') }}" class="btn btn-primary">Back to Customers</a>
            @endif
        </div>
    </div>
</div>
@endsection