@extends('layouts.master')
@section('title', 'Sales Reports')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sales Reports</h5>
                    <div>
                        <form action="{{ route('manager.reports.sales') }}" method="GET" class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text">From</span>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">To</span>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            @if(request('start_date') || request('end_date'))
                                <a href="{{ route('manager.reports.sales') }}" class="btn btn-secondary">Clear</a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Sales</h6>
                                    <h3 class="mb-0">${{ number_format($totalSales, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Orders</h6>
                                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Average Order Value</h6>
                                    <h3 class="mb-0">${{ number_format($averageOrderValue, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Top Selling Product</h6>
                                    <h3 class="mb-0">{{ $topProduct ?? 'N/A' }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                        <td>#{{ $sale->id }}</td>
                                        <td>{{ $sale->customer ? $sale->customer->name : 'Unknown Customer' }}</td>
                                        <td>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($sale->items as $item)
                                                    <li>{{ $item->quantity }}x {{ $item->product ? $item->product->name : 'Unknown Product' }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>${{ number_format($sale->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $sale->status === 'completed' ? 'success' : ($sale->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($sale->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No sales data available for the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($sales->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $sales->links() }}
                        </div>
                    @endif

                    <!-- Export Options -->
                    <div class="mt-4">
                        <h6>Export Report</h6>
                        <div class="btn-group">
                            <a href="{{ route('manager.reports.export', ['type' => 'sales', 'format' => 'pdf'] + request()->query()) }}" class="btn btn-secondary">
                                Export as PDF
                            </a>
                            <a href="{{ route('manager.reports.export', ['type' => 'sales', 'format' => 'excel'] + request()->query()) }}" class="btn btn-secondary">
                                Export as Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 