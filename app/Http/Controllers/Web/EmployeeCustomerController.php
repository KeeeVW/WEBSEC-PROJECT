<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show customers managed by the employee
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all customers instead of just the ones managed by this employee
        $customers = User::role('customer')->get();
        
        return view('employee_customers.index', compact('customers'));
    }
    
    /**
     * Show form to add a customer to employee
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get all customers that are not already associated with this user
        $availableCustomers = User::role('customer')
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('customer_id')
                    ->from('employee_customers')
                    ->where('employee_id', $user->id);
            })
            ->get();
        
        return view('employee_customers.create', compact('availableCustomers'));
    }
    
    /**
     * Store a new employee-customer relationship
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id'
        ]);
        
        // Check if customer has the 'customer' role
        $customer = User::findOrFail($validated['customer_id']);
        if (!$customer->isCustomer()) {
            return redirect()->route('employee_customers.create')
                ->with('error', 'Selected user is not a customer');
        }
        
        // Create relationship
        EmployeeCustomer::firstOrCreate([
            'employee_id' => $user->id,
            'customer_id' => $validated['customer_id']
        ]);
        
        return redirect()->route('employee_customers.index')
            ->with('success', 'Customer added successfully');
    }
    
    /**
     * Remove a customer from employee management
     */
    public function destroy(User $customer)
    {
        $user = Auth::user();
        
        // Delete relationship
        EmployeeCustomer::where('employee_id', $user->id)
            ->where('customer_id', $customer->id)
            ->delete();
        
        return redirect()->route('employee_customers.index')
            ->with('success', 'Customer removed successfully');
    }
} 