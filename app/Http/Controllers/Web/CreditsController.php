<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCredit;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreditsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the index page for credits - redirects to appropriate view based on role
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isEmployee() || $user->isAdmin()) {
            return $this->employeeIndex();
        }
        
        return $this->customerIndex();
    }
    
    /**
     * Display the customer credits index page
     */
    private function customerIndex()
    {
        $user = Auth::user();
        $creditBalance = $user->getCreditAmount();
        $transactions = CreditTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('credits.customer_index', compact('creditBalance', 'transactions'));
    }
    
    /**
     * Display the employee credits index page
     */
    private function employeeIndex()
    {
        $user = Auth::user();
        
        // Get all customers with customer role for employees to add credits to
        $customers = User::role('customer')->get();
        
        return view('credits.employee_index', compact('customers'));
    }
    
    /**
     * Show form to add credit to a customer (for employees/admin)
     */
    public function addForm(User $customer)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->isEmployee() && !$user->isAdmin() && !$user->hasPermissionTo('manage_credits')) {
            return redirect()->route('users')
                ->with('error', 'You do not have permission to add credit to customers');
        }
        
        // Get customer's current credit balance
        $creditBalance = $customer->getCreditAmount();
        
        return view('credits.add', compact('customer', 'creditBalance'));
    }
    
    /**
     * Add credit to a customer (for employees/admin)
     */
    public function addCredit(Request $request, User $customer)
    {
        $user = Auth::user();
        
        // Check if user is an employee or admin
        if (!$user->isEmployee() && !$user->isAdmin() && !$user->hasPermissionTo('manage_credits')) {
            return redirect()->route('users')
                ->with('error', 'You do not have permission to add credit to customers');
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);
        
        // Ensure amount is positive
        if ($validated['amount'] <= 0) {
            return redirect()->back()
                ->with('error', 'Credit amount must be a positive value');
        }
        
        try {
            DB::transaction(function () use ($validated, $customer, $user) {
                // Get or create user credit record
                $userCredit = UserCredit::firstOrCreate(
                    ['user_id' => $customer->id],
                    ['amount' => 0]
                );
                
                // Update amount - ensure it's applied properly
                $previousAmount = $userCredit->amount;
                $userCredit->amount = $previousAmount + $validated['amount'];
                $userCredit->save();
                
                // Create transaction record with proper admin/employee ID
                CreditTransaction::create([
                    'user_id' => $customer->id,
                    'amount' => $validated['amount'],
                    'type' => 'credit',
                    'description' => $validated['description'] ?? 'Added by ' . $user->name,
                    'added_by' => $user->id,
                ]);
                
                // Log the transaction for auditing
                \Illuminate\Support\Facades\Log::info(
                    "Credit added: {$validated['amount']} to user {$customer->id} by {$user->id}. " .
                    "Previous balance: {$previousAmount}, New balance: {$userCredit->amount}"
                );
            });
            
            // Redirect to admin credits page if admin, otherwise to credits index
            if ($user->isAdmin()) {
                return redirect()->route('credits.admin')
                    ->with('success', 'Credit added successfully to ' . $customer->name);
            } else {
                return redirect()->route('credits.index')
                    ->with('success', 'Credit added successfully to ' . $customer->name);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Credit addition failed: " . $e->getMessage());
            return redirect()->route('credits.add_form', $customer->id)
                ->with('error', 'Failed to add credit: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form for customers to add credit to their own account
     */
    public function selfAddForm()
    {
        $user = Auth::user();
        
        if (!$user->isCustomer()) {
            return redirect()->route('users')
                ->with('error', 'Only customers can add credit to their account');
        }
        
        return view('credits.self_add');
    }
    
    /**
     * Process customer adding credit to their own account
     */
    public function selfAddStore(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isCustomer()) {
            return redirect()->route('users')
                ->with('error', 'Only customers can add credit to their account');
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer',
            'description' => 'nullable|string|max:255',
        ]);
        
        try {
            DB::transaction(function () use ($validated, $user) {
                // Get or create user credit record
                $userCredit = UserCredit::firstOrCreate(
                    ['user_id' => $user->id],
                    ['amount' => 0]
                );
                
                // Update amount
                $userCredit->amount += $validated['amount'];
                $userCredit->save();
                
                // Create transaction record
                CreditTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $validated['amount'],
                    'type' => 'credit',
                    'description' => $validated['description'] ?? 'Self deposit via ' . $validated['payment_method'],
                    'added_by' => $user->id,
                ]);
            });
            
            return redirect()->route('credits.index')
                ->with('success', 'Credit added successfully to your account');
        } catch (\Exception $e) {
            return redirect()->route('credits.self_add')
                ->with('error', 'Failed to add credit: ' . $e->getMessage());
        }
    }
    
    /**
     * Admin can view all users with credit balances
     */
    public function adminIndex()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('users')
                ->with('error', 'You do not have permission to access this page');
        }
        
        $customers = User::role('customer')
            ->leftJoin('user_credits', 'users.id', '=', 'user_credits.user_id')
            ->select('users.*', DB::raw('COALESCE(user_credits.amount, 0) as credit_amount'))
            ->orderBy('credit_amount', 'desc')
            ->get();
        
        return view('credits.admin_index', compact('customers'));
    }
} 