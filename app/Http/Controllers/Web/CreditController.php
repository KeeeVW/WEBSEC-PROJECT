<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CreditTransaction;
use App\Models\EmployeeCustomer;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    protected $creditService;
    
    public function __construct(CreditService $creditService)
    {
        $this->middleware('auth');
        $this->creditService = $creditService;
    }
    
    /**
     * Show the credit management page
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isEmployee()) {
            // For employees, show their customers
            $customers = $user->customers()->get();
            return view('credits.employee_index', compact('customers'));
        } else {
            // For customers, show their credit
            $creditBalance = $this->creditService->getBalance($user);
            $transactions = CreditTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('credits.customer_index', compact('creditBalance', 'transactions'));
        }
    }
    
    /**
     * Show the form to add credit to a customer
     */
    public function addCreditForm(User $customer)
    {
        $user = Auth::user();
        
        // Check if user is an employee or admin
        if (!$user->isEmployee() && !$user->isAdmin()) {
            return redirect()->route('credits.index')
                ->with('error', 'You do not have permission to add credit');
        }
        
        // Check if this customer is associated with this employee
        if ($user->isEmployee() && !$user->customers->contains($customer)) {
            // First, try to create the association
            EmployeeCustomer::firstOrCreate([
                'employee_id' => $user->id,
                'customer_id' => $customer->id
            ]);
        }
        
        $creditBalance = $this->creditService->getBalance($customer);
        
        return view('credits.add', compact('customer', 'creditBalance'));
    }
    
    /**
     * Process adding credit to a customer
     */
    public function addCredit(Request $request, User $customer)
    {
        $user = Auth::user();
        
        // Check if user is an employee or admin
        if (!$user->isEmployee() && !$user->isAdmin()) {
            return redirect()->route('credits.index')
                ->with('error', 'You do not have permission to add credit');
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ]);
        
        // Add credit
        $success = $this->creditService->addCredit(
            $customer,
            $validated['amount'],
            $user,
            $validated['description'] ?? null
        );
        
        if ($success) {
            return redirect()->route('credits.add_form', $customer)
                ->with('success', 'Credit added successfully');
        } else {
            return redirect()->route('credits.add_form', $customer)
                ->with('error', 'Failed to add credit');
        }
    }
    
    /**
     * Show credit transaction history
     */
    public function transactions()
    {
        $user = Auth::user();
        $transactions = CreditTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('credits.transactions', compact('transactions'));
    }
} 