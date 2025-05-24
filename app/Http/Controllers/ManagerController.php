<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:manager']);
    }

    public function inventory()
    {
        $products = Product::all();
        return view('manager.inventory', compact('products'));
    }

    public function reports()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        return view('manager.reports', compact('totalProducts', 'totalOrders', 'recentOrders'));
    }

    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('manager.orders', compact('orders'));
    }
} 