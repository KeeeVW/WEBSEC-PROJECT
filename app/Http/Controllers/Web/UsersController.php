<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        // Get current user
        $user = auth()->user();
        
        // If user is not logged in or not an admin, redirect them
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'You do not have permission to access the users list');
        }
        
        // Create the base query
        $query = User::select('*');
        
        // Apply keyword search if provided
        if ($request->keywords) {
            $query->where("name", "like", "%$request->keywords%");
        }
        
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
    	$this->validate($request, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    
    // Assign Customer role to newly registered users
    $customerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    $user->assignRole($customerRole);
    
    \App\Models\UserCredit::create([
        'user_id' => $user->id,
        'amount' => 0.00
    ]);

    Auth::login($user);

    return redirect('/');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
    	
    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        
        if ($user->isBlocked()) {
            Auth::logout();
            return redirect()->back()->withInput($request->only('email'))
                ->withErrors('Your account have been blocked.');
        }
        
        Auth::setUser($user);
        
        if ($user->is_using_temp_password) {
            return redirect()->route('change_temp_password');
        }

        return redirect('/');
    }

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }

        $user = $user??auth()->user();
        
        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        
        // Get credit information and other customer details if this is a customer
        $creditAmount = $user->isCustomer() ? $user->getCreditAmount() : 0;
        $creditHistory = $user->isCustomer() ? \App\Models\CreditTransaction::where('user_id', $user->id)->take(5)->get() : null;
        $purchaseHistory = $user->isCustomer() ? \App\Models\Purchase::where('user_id', $user->id)->take(5)->get() : null;

        return view('users.profile', compact('user', 'permissions', 'creditAmount', 'creditHistory', 'purchaseHistory'));
    }

    public function edit(Request $request, User $user = null) {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }
   
        $user = $user??auth()->user();
        
        // For employees - they can only edit themselves or customer accounts
        if(auth()->user()->isEmployee() && !auth()->user()->isAdmin()) {
            // If trying to edit another user
            if(auth()->id() != $user->id) {
                // Check if that user is a customer, otherwise redirect
                if(!$user->hasRole('customer')) {
                    return redirect()->route('users')
                        ->with('error', 'You can only edit customer accounts');
                }
            }
        }
        
        // For regular users, check edit_users permission
        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) {
                return redirect()->route('profile');
            }
        }
    
        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }      

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }

        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) {
                return redirect()->route('profile');
            }
        }

        $user->name = $request->name;
        
        if(auth()->check() && auth()->user()->hasPermissionTo('admin_users')) {
            $user->is_admin = $request->has('is_admin') ? 1 : 0;
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            Artisan::call('cache:clear');
        }

        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function delete(Request $request, User $user) {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }

        if(!auth()->user()->hasPermissionTo('delete_users')) {
            return redirect()->route('users');
        }

        $user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) {
                return redirect()->route('profile');
            }
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {
            return redirect()->route('profile');
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function forgotPassword()
    {
        return view('users.forgot_password');
    }

    public function processForgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'This email is not registered in our system.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Determine if we use the basic or professional reset method based on config
        // Default to professional if not specified
        $useBasicReset = config('app.use_basic_password_reset', false);
        
        if ($useBasicReset) {
            // Basic password reset (send temporary password)
            $tempPassword = Str::random(10);
            
            // Store the reset record
            PasswordReset::create([
                'email' => $user->email,
                'token' => bcrypt($tempPassword),
                'is_temp_password' => true,
                'created_at' => now(),
                'expires_at' => now()->addDays(1),
            ]);
            
            // Update user to flag they're using temp password
            $user->password = bcrypt($tempPassword);
            $user->is_using_temp_password = true;
            $user->save();
            
            // Send email with temp password
            Mail::send('emails.reset_password_temp', ['user' => $user, 'tempPassword' => $tempPassword], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Your Temporary Password');
            });
            
            return redirect()->route('login')->with('success', 'A temporary password has been sent to your email.');
        } else {
            // Professional password reset (send secure link)
            $token = Str::random(60);
            
            // Delete any existing reset tokens for this email
            PasswordReset::where('email', $user->email)->delete();
            
            // Store the reset record
            PasswordReset::create([
                'email' => $user->email,
                'token' => $token,
                'is_temp_password' => false,
                'created_at' => now(),
                'expires_at' => now()->addHour(),
            ]);
            
            // Generate reset link
            $resetLink = route('reset_password_token', ['token' => $token]) . '?email=' . urlencode($user->email);
            
            // Send email with reset link
            Mail::send('emails.reset_password_link', ['user' => $user, 'resetLink' => $resetLink], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Your Password');
            });
            
            return redirect()->route('login')->with('success', 'A password reset link has been sent to your email.');
        }
    }

    // Original security question based reset (keeping for backward compatibility)
    public function resetPassword(User $user)
    {
        return view('users.security_question', compact('user'));
    }

    public function processResetPassword(Request $request, User $user)
    {
        $this->validate($request, [
            'security_answer' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        // Check if the security answer is correct
        if ($request->security_answer !== $user->security_answer) {
            return redirect()->back()->withErrors(['security_answer' => 'The answer to the security question is incorrect.']);
        }

        // Update the password
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now log in with your new password.');
    }
    
    // Professional password reset methods (with token)
    public function resetPasswordWithToken(Request $request, $token)
    {
        $email = $request->query('email');
        
        // Check if token is valid
        $reset = PasswordReset::where('token', $token)
            ->where('email', $email)
            ->where('is_temp_password', false)
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$reset) {
            return redirect()->route('login')->with('error', 'Invalid or expired password reset link.');
        }
        
        return view('users.reset_password', ['token' => $token, 'email' => $email]);
    }
    
    public function processResetPasswordWithToken(Request $request, $token)
    {
        $email = $request->input('email');
        
        $this->validate($request, [
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'email' => 'required|email|exists:users,email',
        ]);
        
        // Check if token is valid
        $reset = PasswordReset::where('token', $token)
            ->where('email', $email)
            ->where('is_temp_password', false)
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$reset) {
            return redirect()->route('login')->with('error', 'Invalid or expired password reset link.');
        }
        
        // Find the user and update password
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        
        // Delete the token after successful reset
        $reset->delete();
        
        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now log in with your new password.');
    }
    
    // Basic password reset methods (temp password)
    public function changeTempPassword()
    {
        // Only allow access if user is using a temp password
        if (!Auth::check() || !Auth::user()->is_using_temp_password) {
            return redirect()->route('home');
        }
        
        return view('users.change_temp_password');
    }
    
    public function updateTempPassword(Request $request)
    {
        // Only allow access if user is using a temp password
        if (!Auth::check() || !Auth::user()->is_using_temp_password) {
            return redirect()->route('home');
        }
        
        $this->validate($request, [
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);
        
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->is_using_temp_password = false;
        $user->save();
        
        return redirect()->route('home')->with('success', 'Your password has been updated successfully.');
    }

    /**
     * Show form to create a new employee account (admin only)
     */
    public function createEmployee()
    {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if(!auth()->user()->isAdmin()) {
            return redirect()->route('users')
                ->with('error', 'Only administrators can create employee accounts');
        }
        
        return view('users.create_employee');
    }

    /**
     * Process the creation of a new employee account (admin only)
     */
    public function storeEmployee(Request $request)
    {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if(!auth()->user()->isAdmin()) {
            return redirect()->route('users')
                ->with('error', 'Only administrators can create employee accounts');
        }
        
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->has('is_admin') ? 1 : 0,
            ]);
            
            // Assign Employee role
            $employeeRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
            $user->assignRole($employeeRole);
            
            // If is_admin is checked, also assign Admin role
            if($request->has('is_admin')) {
                $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
                $user->assignRole($adminRole);
            }
            
            // Clear permissions cache
            Artisan::call('cache:clear');
            
            DB::commit();
            
            return redirect()->route('users')
                ->with('success', 'Employee account created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Failed to create employee account: ' . $e->getMessage());
        }
    }

    /**
     * Displays customers for employees to manage
     */
    public function employeeCustomers(Request $request)
    {
        // Check if user is logged in
        if(!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user is an employee or admin
        $user = Auth::user();
        if (!$user->isEmployee() && !$user->isAdmin() && !$user->hasPermissionTo('manage_customers')) {
            return redirect()->route('login')
                ->with('error', 'You do not have permission to view this page');
        }
        
        // Get only customer accounts
        $query = User::role('customer');
        
        // Apply keyword search if provided
        if ($request->keywords) {
            $query->where("name", "like", "%$request->keywords%");
        }
        
        $customers = $query->get();
        
        return view('employee_customers.index', compact('customers'));
    }

    /**
     * Ensure users have proper roles
     */
    public function ensureUserRoles()
    {
        // Get all users without roles
        $usersWithoutRoles = User::whereDoesntHave('roles')->get();
        
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $customerRole = Role::where('name', 'customer')->first();
        
        foreach ($usersWithoutRoles as $user) {
            // If user is marked as admin, assign admin role
            if ($user->is_admin) {
                $user->assignRole($adminRole);
            } else {
                // Default to customer role
                $user->assignRole($customerRole);
            }
        }
        
        return redirect()->back()->with('success', 'User roles have been updated.');
    }

    /**
     * Toggle the block status of a user (block or unblock)
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleBlockStatus(User $user)
    {
        // Only employees and admins can block/unblock users
        if (!Auth::user()->isAdmin() && !Auth::user()->isEmployee()) {
            abort(403, 'You do not have permission to perform this action');
        }

        // Can't block yourself
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot block yourself');
        }

        // Toggle the block status
        if ($user->is_blocked) {
            // Unblock the user
            $user->is_blocked = false;
            $user->save();
            return redirect()->back()->with('success', 'User has been unblocked successfully');
        } else {
            // Block the user
            $user->is_blocked = true;
            $user->save();
            return redirect()->back()->with('success', 'User has been blocked successfully');
        }
    }
} 