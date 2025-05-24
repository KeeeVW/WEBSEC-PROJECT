<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Web\RolesController;
use App\Http\Controllers\Web\PermissionsController;
use App\Http\Controllers\Web\CreditsController;
use App\Http\Controllers\Web\EmployeeCustomerController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\GitHubAuthController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LinkedInAuthController;
use App\Http\Controllers\TwitterAuthController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

// Password reset routes
Route::get('forgot-password', [UsersController::class, 'forgotPassword'])->name('password.request');
Route::post('forgot-password', [UsersController::class, 'processForgotPassword'])->name('password.email');
Route::post('process-forgot-password', [UsersController::class, 'processForgotPassword'])->name('process_forgot_password');
Route::get('reset-password-token/{token}', [UsersController::class, 'resetPasswordWithToken'])->name('reset_password_token');

// Temporary password reset (Basic method)
Route::get('change-temp-password', [UsersController::class, 'changeTempPassword'])->name('change_temp_password');
Route::post('update-temp-password', [UsersController::class, 'updateTempPassword'])->name('update_temp_password');

// Professional password reset
Route::get('reset-password/{token}', [UsersController::class, 'resetPasswordWithToken'])->name('password.reset');
Route::post('reset-password/{token}', [UsersController::class, 'processResetPasswordWithToken'])->name('password.update');

// Legacy password reset routes (with security questions)
Route::get('reset-password/{user}', [UsersController::class, 'resetPassword'])->name('reset_password');
Route::post('reset-password/{user}', [UsersController::class, 'processResetPassword'])->name('process_reset_password');

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/show/{product}', [ProductsController::class, 'showProduct'])->name('products_show');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
Route::post('products/purchase/{product}', [ProductsController::class, 'purchase'])->name('products_purchase');
Route::get('purchase-history', [ProductsController::class, 'purchaseHistory'])->name('purchase_history');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/bill', function () {
    return view('bill'); 
 });

 Route::get('/transcript', function () {
    return view('transcript');
});
Route::get('/calculator', function () {
    return view('calculator');
 });
 
 Route::get('/gpa-calculator', function () {
     $courses = [
         ['code' => 'Web and Security Technologies ', 'title' => '', 'credit' => 3],
         ['code' => 'Linux and Shell Programming ', 'title' => ' ', 'credit' => 3],
         ['code' => 'Network Operation and Managment', 'title' => ' ', 'credit' => 3],
         ['code' => 'Digital Forensics Fundamental ', 'title' => ' ', 'credit' => 3],
     ];
     
     return view('gpa-calculator', compact('courses'));
 });

 Route::resource('grades', GradeController::class);

 Route::resource('questions', QuestionController::class);
Route::get('exam/start', [ExamController::class, 'start'])->name('exam.start');
Route::post('exam/submit', [ExamController::class, 'submit'])->name('exam.submit');

Route::get('/test-connection', function () {
    return 'Connection test successful!';
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection successful! Database: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});

Route::get('/test-exam', function () {
    $questions = [
        (object)[
            'id' => 1,
            'question' => 'What is the correct way to declare a variable in PHP?',
            'option_a' => '$variable = value;',
            'option_b' => 'variable = value;',
            'option_c' => 'var variable = value;',
            'option_d' => 'variable := value;',
            'correct_answer' => 'A'
        ],
        (object)[
            'id' => 2,
            'question' => 'Which SQL statement is used to retrieve data from a database?',
            'option_a' => 'GET',
            'option_b' => 'SELECT',
            'option_c' => 'EXTRACT',
            'option_d' => 'OPEN',
            'correct_answer' => 'B'
        ],
        (object)[
            'id' => 3,
            'question' => 'Which tag is used to define an HTML hyperlink?',
            'option_a' => '<link>',
            'option_b' => '<a>',
            'option_c' => '<href>',
            'option_d' => '<hyperlink>',
            'correct_answer' => 'B'
        ]
    ];
    
    return view('users.exam.start', compact('questions'));
});

Route::get('/test-result', function () {
    return view('users.exam.result', [
        'score' => 2,
        'total' => 3
    ]);
});

// Add these routes for roles and permissions management
Route::group(['middleware' => ['auth', 'permission:admin_users']], function () {
    Route::resource('roles', RolesController::class);
});

// GitHub Authentication Routes
Route::get('/auth/github', [GitHubAuthController::class, 'redirect'])->name('auth.github');
Route::get('/auth/github/callback', [GitHubAuthController::class, 'callback'])->name('auth.github.callback');

// User Management Routes
Route::prefix('users')->name('users_')->group(function () {
    Route::get('/create-employee', [UsersController::class, 'createEmployee'])->name('create_employee');
    Route::post('/create-employee', [UsersController::class, 'storeEmployee'])->name('create_employee_post');
    Route::get('/toggle-block/{user}', [UsersController::class, 'toggleBlockStatus'])->name('toggle_block');
    Route::get('/ensure-roles', [UsersController::class, 'ensureUserRoles'])->name('ensure_roles');
});

// Credit Management Routes
Route::prefix('credits')->name('credits.')->group(function () {
    Route::get('/', [CreditsController::class, 'index'])->name('index');
    Route::get('/admin', [CreditsController::class, 'adminIndex'])->name('admin');
    Route::get('/add/{customer}', [CreditsController::class, 'addForm'])->name('add_form');
    Route::post('/add/{customer}', [CreditsController::class, 'addCredit'])->name('add');
    Route::get('/transactions', [CreditsController::class, 'index'])->name('transactions');
    Route::get('/self-add', [CreditsController::class, 'selfAddForm'])->name('self_add');
    Route::post('/self-add', [CreditsController::class, 'selfAddStore'])->name('self_add_store');
});

// Employee Management Routes
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/customers', [EmployeeController::class, 'listCustomers'])->name('customers');
    Route::get('/manage-customers', [EmployeeCustomerController::class, 'index'])->name('manage_customers');
});

// Employee-Customer Management Routes
Route::prefix('employee-customers')->name('employee_customers.')->group(function () {
    Route::get('/', [EmployeeCustomerController::class, 'index'])->name('index');
    Route::get('/create', [EmployeeCustomerController::class, 'create'])->name('create');
    Route::post('/', [EmployeeCustomerController::class, 'store'])->name('store');
    Route::delete('/{customer}', [EmployeeCustomerController::class, 'destroy'])->name('destroy');
});

// Role Management Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
});

// Permission Management Routes
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [PermissionsController::class, 'index'])->name('index');
    Route::get('/create', [PermissionsController::class, 'create'])->name('create');
    Route::post('/', [PermissionsController::class, 'store'])->name('store');
    Route::get('/{permission}/edit', [PermissionsController::class, 'edit'])->name('edit');
    Route::put('/{permission}', [PermissionsController::class, 'update'])->name('update');
    Route::delete('/{permission}', [PermissionsController::class, 'destroy'])->name('destroy');
});

// Temporary test route for cryptography
// Route::get('/cryptography', function () {
//     return 'Cryptography route is working!';
// });

// Customer Favorite Products Routes
Route::middleware(['auth'])->group(function () {
    Route::match(['post', 'delete'], '/products/{product}/favorite', [ProductsController::class, 'favorite'])->name('products.favorite');
    Route::get('/favorites', [ProductsController::class, 'favoritesList'])->name('products.favorites.list');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', [UsersController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::get('/resend-verification', [UsersController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');

// Manager Routes
Route::middleware(['auth', \App\Http\Middleware\CheckManagerPermissions::class.':orders'])->group(function () {
    Route::get('/manager/orders', [\App\Http\Controllers\Manager\OrderController::class, 'index'])->name('manager.orders.index');
    Route::get('/manager/orders/create', [\App\Http\Controllers\Manager\OrderController::class, 'create'])->name('manager.orders.create');
    Route::post('/manager/orders', [\App\Http\Controllers\Manager\OrderController::class, 'store'])->name('manager.orders.store');
    Route::get('/manager/orders/{order}', [\App\Http\Controllers\Manager\OrderController::class, 'show'])->name('manager.orders.show');
    Route::put('/manager/orders/{order}', [\App\Http\Controllers\Manager\OrderController::class, 'update'])->name('manager.orders.update');
    Route::delete('/manager/orders/{order}', [\App\Http\Controllers\Manager\OrderController::class, 'destroy'])->name('manager.orders.destroy');
});

Route::middleware(['auth', \App\Http\Middleware\CheckManagerPermissions::class.':inventory'])->group(function () {
    Route::get('/manager/inventory', [\App\Http\Controllers\Manager\InventoryController::class, 'index'])->name('manager.inventory.index');
    Route::get('/manager/inventory/create', [\App\Http\Controllers\Manager\InventoryController::class, 'create'])->name('manager.inventory.create');
    Route::post('/manager/inventory', [\App\Http\Controllers\Manager\InventoryController::class, 'store'])->name('manager.inventory.store');
    Route::get('/manager/inventory/{item}/edit', [\App\Http\Controllers\Manager\InventoryController::class, 'edit'])->name('manager.inventory.edit');
    Route::put('/manager/inventory/{item}', [\App\Http\Controllers\Manager\InventoryController::class, 'update'])->name('manager.inventory.update');
    Route::delete('/manager/inventory/{item}', [\App\Http\Controllers\Manager\InventoryController::class, 'destroy'])->name('manager.inventory.destroy');
});

Route::middleware(['auth', \App\Http\Middleware\CheckManagerPermissions::class.':reports'])->group(function () {
    Route::get('/manager/reports', [\App\Http\Controllers\Manager\ReportController::class, 'index'])->name('manager.reports.index');
    Route::get('/manager/reports/customers', [\App\Http\Controllers\Manager\ReportController::class, 'customers'])->name('manager.reports.customers');
    Route::get('/manager/reports/export', [\App\Http\Controllers\Manager\ReportController::class, 'export'])->name('manager.reports.export');
});

// Manager Reports Routes
Route::middleware(['auth', \App\Http\Middleware\CheckManagerPermissions::class.':reports'])->prefix('manager/reports')->name('manager.reports.')->group(function () {
    Route::get('/sales', [App\Http\Controllers\Manager\ReportController::class, 'sales'])->name('sales');
    Route::get('/sales/export/{format}', [App\Http\Controllers\Manager\ReportController::class, 'exportSales'])->name('sales.export');
    Route::get('/inventory', [App\Http\Controllers\Manager\ReportController::class, 'inventory'])->name('inventory');
    Route::get('/customers', [App\Http\Controllers\Manager\ReportController::class, 'customers'])->name('customers');
});

// Support Routes
Route::middleware(['auth', 'role:support'])->prefix('support')->name('support.')->group(function () {
    // Ticket Routes
    Route::get('/tickets', [SupportController::class, 'tickets'])->name('tickets.index');
    Route::get('/tickets/create', [SupportController::class, 'createTicket'])->name('tickets.create');
    Route::post('/tickets', [SupportController::class, 'storeTicket'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [SupportController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{ticket}/respond', [SupportController::class, 'respondTicket'])->name('tickets.respond');
    
    // Order Routes
    Route::get('/orders', [SupportController::class, 'orders'])->name('orders.index');
    Route::get('/orders/create', [SupportController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [SupportController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [SupportController::class, 'showOrder'])->name('orders.show');
});

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('/auth/linkedin', [LinkedInAuthController::class, 'redirect'])->name('auth.linkedin');
Route::get('/auth/linkedin/callback', [LinkedInAuthController::class, 'callback'])->name('auth.linkedin.callback');

Route::get('/auth/twitter', [TwitterAuthController::class, 'redirect'])->name('auth.twitter');
Route::get('/auth/twitter/callback', [TwitterAuthController::class, 'callback'])->name('auth.twitter.callback');

// Standard Laravel Authentication Routes (including password reset)
// These were added previously but are causing issues with the controller.
// We will rely on the existing UsersController methods instead.
// Route::get('email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');
//
// Route::get('forgot-password', \Illuminate\Auth\Controllers\PasswordController::class)
//     ->middleware('guest')
//     ->name('password.request');
//
// Route::post('forgot-password', \Illuminate\Auth\Controllers\PasswordController::class)
//     ->middleware('guest')
//     ->name('password.email');
//
// Route::get('reset-password/{token}', \Illuminate\Auth\Controllers\PasswordController::class)
//     ->middleware('guest')
//     ->name('password.reset');
//
// Route::post('reset-password', \Illuminate\Auth\Controllers\PasswordController::class)
//     ->middleware('guest')
//     ->name('password.update');
