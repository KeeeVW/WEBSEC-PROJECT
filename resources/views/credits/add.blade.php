@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add Credit</h2>

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

    <div class="card mb-4">
        <div class="card-header">
            <h4>Customer Information</h4>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Current Balance:</strong> ${{ number_format($creditBalance, 2) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Add Credit</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('credits.add', $customer->id) }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="amount">Amount ($)</label>
                    <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required>
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="description">Description (Optional)</label>
                    <input type="text" class="form-control" id="description" name="description">
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Add Credit</button>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
                    @else
                    <a href="{{ route('employee.customers') }}" class="btn btn-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 