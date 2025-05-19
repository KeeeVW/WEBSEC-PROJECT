@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Your Credit Account</h2>

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
            <h4>Current Balance</h4>
        </div>
        <div class="card-body">
            <h2>${{ number_format($creditBalance, 2) }}</h2>
            <div class="mt-3">
                <a href="{{ route('credits.self_add') }}" class="btn btn-success">Add Credit to Your Account</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Recent Transactions</h4>
        </div>
        <div class="card-body">
            @if($transactions->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td>{{ $transaction->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No transactions found.</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('purchase_history') }}" class="btn btn-primary">View Purchase History</a>
    </div>
</div>
@endsection 