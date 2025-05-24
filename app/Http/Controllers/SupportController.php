<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:support']);
    }

    public function tickets()
    {
        try {
            // Check if user has support role
            if (!auth()->user()->hasRole('support')) {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }

            $tickets = \App\Models\SupportTicket::with('user', 'responses')->get();
            return view('support.tickets.index', compact('tickets'));
        } catch (\Exception $e) {
            \Log::error('Support tickets error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'An error occurred while accessing tickets.');
        }
    }

    public function showTicket(Ticket $ticket)
    {
        $ticket->load(['user', 'replies.user']);
        return view('support.tickets.show', compact('ticket'));
    }

    public function createTicket()
    {
        return view('support.tickets.create');
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high'
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'status' => 'open'
        ]);

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function respondTicket(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string'
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message']
        ]);

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'Response added successfully.');
    }

    public function orders()
    {
        try {
            // Check if user has support role
            if (!auth()->user()->hasRole('support')) {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }

            $orders = \App\Models\Order::with('user', 'items')->get();
            return view('support.orders.index', compact('orders'));
        } catch (\Exception $e) {
            \Log::error('Support orders error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'An error occurred while accessing orders.');
        }
    }

    public function showOrder(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return view('support.orders.show', compact('order'));
    }

    public function createOrder()
    {
        try {
            // Check if user has support role
            if (!auth()->user()->hasRole('support')) {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }

            return view('support.orders.create');
        } catch (\Exception $e) {
            \Log::error('Support create order error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'An error occurred while accessing create order.');
        }
    }

    public function storeOrder(Request $request)
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
            'customer_id' => $validated['customer_id'],
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
            ->route('support.orders.show', $order)
            ->with('success', 'Order created successfully.');
    }
} 