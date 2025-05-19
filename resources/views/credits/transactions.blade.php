@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Credit Transaction History</h2>

    <div class="mb-3">
        <a href="{{ route('credits.index') }}" class="btn btn-primary">Back to Credits</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>All Transactions</h4>
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
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if($transaction->added_by && $transaction->addedByUser)
                                        {{ $transaction->addedByUser->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No transactions found.</p>
            @endif
        </div>
    </div>
</div>
@endsection 