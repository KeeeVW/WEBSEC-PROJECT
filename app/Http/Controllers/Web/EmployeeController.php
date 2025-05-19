<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Constructor with middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of customers for employees
     */
    public function listCustomers()
    {
        // Check if user is an employee or admin
        $user = Auth::user();
        if (!$user->isEmployee() && !$user->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'You do not have permission to access this page');
        }
        
        // Redirect to the manage customers page
        return redirect()->route('employee.manage_customers');
    }
    
    /**
     * Display dashboard with employee actions
     */
    public function dashboard()
    {
        // Check if user is an employee or admin
        $user = Auth::user();
        if (!$user->isEmployee() && !$user->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'You do not have permission to access this page');
        }
        
        // Get stats for the dashboard
        $customerCount = User::role('customer')->count();
        $myCustomerCount = $user->customers()->count();
        
        return view('employee.dashboard', [
            'customerCount' => $customerCount,
            'myCustomerCount' => $myCustomerCount
        ]);
    }
} 