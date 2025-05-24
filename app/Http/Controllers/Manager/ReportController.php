<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        // Debug: Log the request parameters
        \Log::info('Sales Report Request:', [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        // Base query for all orders with eager loading
        $query = Order::with(['customer', 'items.product'])
            ->select('orders.*');

        // Apply date filters if provided
        if ($request->filled('start_date')) {
            $query->whereDate('orders.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('orders.created_at', '<=', $request->end_date);
        }

        // Debug: Log the query SQL
        \Log::info('Sales Query SQL:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // Get paginated orders for display
        $sales = $query->latest()->paginate(10);

        // Debug: Log the paginated results count
        \Log::info('Paginated Sales Count:', [
            'count' => $sales->count(),
            'total' => $sales->total()
        ]);

        // Calculate totals using a fresh query to avoid pagination
        $totalsQuery = Order::query()
            ->select('orders.*');

        if ($request->filled('start_date')) {
            $totalsQuery->whereDate('orders.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $totalsQuery->whereDate('orders.created_at', '<=', $request->end_date);
        }

        // Debug: Log the totals query SQL
        \Log::info('Totals Query SQL:', [
            'sql' => $totalsQuery->toSql(),
            'bindings' => $totalsQuery->getBindings()
        ]);

        // Get all orders for totals calculation with their items
        $allOrders = $totalsQuery->with(['items.product'])->get();

        // Debug: Log raw order data
        \Log::info('Raw Orders Data:', [
            'orders_count' => $allOrders->count(),
            'orders' => $allOrders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                    'created_at' => $order->created_at,
                    'items_count' => $order->items->count()
                ];
            })
        ]);

        // Calculate totals
        $totalSales = $allOrders->sum('total_amount');
        $totalOrders = $allOrders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Debug: Log the calculated totals
        \Log::info('Calculated Totals:', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'allOrdersCount' => $allOrders->count()
        ]);

        // Get top selling product with null checks
        $topProduct = $allOrders->flatMap(function ($order) {
            return $order->items->map(function ($item) {
                return [
                    'name' => $item->product ? $item->product->name : 'Unknown Product',
                    'quantity' => $item->quantity
                ];
            });
        })
        ->groupBy('name')
        ->map(function ($group) {
            return $group->sum('quantity');
        })
        ->sortDesc()
        ->keys()
        ->first() ?? 'No Products Sold';

        // Debug: Log the orders and their items
        \Log::info('Orders and Items:', [
            'orders' => $allOrders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total_amount' => $order->total_amount,
                    'items_count' => $order->items->count(),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->product ? $item->product->name : 'Unknown',
                            'quantity' => $item->quantity,
                            'price' => $item->price
                        ];
                    })
                ];
            })
        ]);

        return view('manager.reports.sales', compact(
            'sales',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'topProduct'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $format = $request->input('format');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base query for all orders
        $query = Order::with(['customer', 'items.product'])
            ->select('orders.*');  // Explicitly select all fields

        // Apply date filters if provided
        if ($startDate) {
            $query->whereDate('orders.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('orders.created_at', '<=', $endDate);
        }

        $sales = $query->latest()->get();
        
        // Calculate totals using a fresh query
        $totalsQuery = Order::query()
            ->select('orders.*');  // Explicitly select all fields

        if ($startDate) {
            $totalsQuery->whereDate('orders.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $totalsQuery->whereDate('orders.created_at', '<=', $endDate);
        }

        $totalSales = $totalsQuery->sum('total_amount');
        $totalOrders = $totalsQuery->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Get top selling product with null checks
        $topProduct = $sales->flatMap(function ($order) {
            return $order->items->map(function ($item) {
                return [
                    'name' => $item->product ? $item->product->name : 'Unknown Product',
                    'quantity' => $item->quantity
                ];
            });
        })
        ->groupBy('name')
        ->map(function ($group) {
            return $group->sum('quantity');
        })
        ->sortDesc()
        ->keys()
        ->first() ?? 'No Products Sold';

        $data = [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'topProduct' => $topProduct,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if ($format === 'pdf') {
            $pdf = PDF::loadView('manager.reports.exports.sales-pdf', $data);
            return $pdf->download('sales-report.pdf');
        } else {
            return Excel::download(new SalesReportExport($data), 'sales-report.xlsx');
        }
    }

    public function inventory()
    {
        $inventoryData = ProductInventory::with('product')
            ->select('product_id', 
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('COUNT(*) as locations')
            )
            ->groupBy('product_id')
            ->paginate(10);

        return view('manager.reports.inventory', compact('inventoryData'));
    }

    public function lowStock()
    {
        $lowStockItems = ProductInventory::with('product')
            ->where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->paginate(10);

        return view('manager.reports.low-stock', compact('lowStockItems'));
    }
} 