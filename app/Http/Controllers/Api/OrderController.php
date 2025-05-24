<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource (user's orders).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Ensure the user is authenticated and is a customer
        if (!$user || !$user->isCustomer()) {
             return $this->sendError('Unauthorized', ['error' => 'Only customers can view orders.'], 403);
        }

        $orders = $user->orders()->with('orderItems.product')->get();

        return $this->sendResponse($orders, 'User orders retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Ensure the user is authenticated and is a customer
        if (!$user || !$user->isCustomer()) {
             return $this->sendError('Unauthorized', ['error' => 'Only customers can place orders.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);

                if (!$product) {
                    DB::rollBack();
                    return $this->sendError('Product not found.', ['product_id' => $item['product_id']], 404);
                }

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return $this->sendError('Insufficient stock for product.', ['product_id' => $product->id, 'available_stock' => $product->stock, 'requested_quantity' => $item['quantity']], 400);
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Store price at the time of order
                ];

                // Decrease product stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Create the order
            $order = $user->orders()->create([
                'total_amount' => $totalAmount,
                'status' => 'pending', // Initial status
            ]);

            // Create order items
            foreach ($orderItemsData as $itemData) {
                 $order->orderItems()->create($itemData);
            }

            DB::commit();

            $order->load('orderItems.product'); // Load relationships for response

            return $this->sendResponse($order, 'Order placed successfully.', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order placement failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return $this->sendError('Failed to place order. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(\App\Models\Order $order)
    {
        $user = Auth::user();

        // Ensure the order belongs to the authenticated user
        if ($order->customer_id !== $user->id) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have access to this order.'], 403);
        }

        $order->load('orderItems.product');

        return $this->sendResponse($order, 'Order retrieved successfully.');
    }

    /**
     * Cancel order.
     *
     * @param  Order  $order
     * @return JsonResponse
     */
    public function cancel(Order $order)
    {
        $user = Auth::user();

        // Ensure the order belongs to the authenticated user
        if ($order->customer_id !== $user->id) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have access to this order.'], 403);
        }

        // Check if the order is pending
        if ($order->status !== 'pending') {
            return $this->sendError('Order cannot be cancelled.', ['error' => 'Only pending orders can be cancelled.'], 400);
        }

        try {
            DB::beginTransaction();

            // Update order status to cancelled
            $order->status = 'cancelled';
            $order->save();

            // Restore product stock
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            DB::commit();

            return $this->sendResponse([], 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order cancellation failed:', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->sendError('Failed to cancel order. Please try again later.');
        }
    }
} 