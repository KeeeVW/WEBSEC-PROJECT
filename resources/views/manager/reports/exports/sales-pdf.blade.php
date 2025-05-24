<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        @if($startDate && $endDate)
            <p>Period: {{ Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }}
        </div>
        <div class="summary-item">
            <strong>Total Orders:</strong> {{ $totalOrders }}
        </div>
        <div class="summary-item">
            <strong>Average Order Value:</strong> ${{ number_format($averageOrderValue, 2) }}
        </div>
        <div class="summary-item">
            <strong>Top Selling Product:</strong> {{ $topProduct }}
        </div>
    </div>

    <table>
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
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->customer->name }}</td>
                    <td>
                        @foreach($sale->items as $item)
                            {{ $item->quantity }}x {{ $item->product->name }}<br>
                        @endforeach
                    </td>
                    <td>${{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html> 