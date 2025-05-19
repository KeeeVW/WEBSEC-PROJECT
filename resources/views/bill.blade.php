@extends('layouts.master')
@section('title', 'MiniTest')
@section('content')
    <h2 class="mb-4 text-center">MiniTest</h2>

    @php
        $items = [
            ['name' => 'Apples', 'quantity' => 2, 'price' => 3.50],
            ['name' => 'Milk', 'quantity' => 1, 'price' => 2.00],
            ['name' => 'Bread', 'quantity' => 1, 'price' => 1.50],
            ['name' => 'Eggs', 'quantity' => 12, 'price' => 4.00],
        ];
        $total = array_reduce($items, fn($sum, $item) => $sum + ($item['quantity'] * $item['price']), 0);
    @endphp

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price per Unit</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Grand Total:</th>
                <th>${{ number_format($total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
@endsection
