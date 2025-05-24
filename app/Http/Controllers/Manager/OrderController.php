<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user' => function($query) {
            $query->select('id', 'name', 'email');
        }, 'items.product'])->latest()->paginate(10);
        return view('manager.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = User::role('customer')->get();
        $products = Product::all();
        return view('manager.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $order = Order::create([
            'user_id' => $validated['customer_id'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'total_amount' => 0 // Will be calculated after items are added
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;

            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal
            ]);
        }

        $order->update(['total_amount' => $total]);

        return redirect()
            ->route('manager.orders.show', $order)
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['user' => function($query) {
            $query->select('id', 'name', 'email');
        }, 'items.product']);
        return view('manager.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $order->update($validated);

        return redirect()->route('manager.orders.show', $order)
            ->with('success', 'Order updated successfully');
    }

    public function destroy(Order $order)
    {
        if ($order->status === 'completed') {
            return back()->with('error', 'Cannot delete completed orders');
        }

        $order->delete();

        return redirect()->route('manager.orders.index')
            ->with('success', 'Order deleted successfully');
    }
} 