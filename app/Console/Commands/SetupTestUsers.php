<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserCredit;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SetupTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up test users (admin, employee, customer) with known passwords';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up test users...');

        // Make sure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Create or ensure permissions exist
        $permissions = [
            'view_users',
            'edit_users',
            'delete_users',
            'admin_users',
            'manage_credits',
            'manage_products',
            'view_customers',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_customers'
        ];
        
        foreach ($permissions as $permissionName) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
        }
        
        // Assign all permissions to admin role
        $adminRole->syncPermissions($permissions);
        
        // Assign limited permissions to employee role
        $employeeRole->syncPermissions([
            'view_users', 
            'edit_users', 
            'manage_credits', 
            'view_customers', 
            'manage_customers',
            'manage_products',
            'add_products',
            'edit_products',
            'delete_products'
        ]);

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@google.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_blocked' => false
            ]
        );
        $admin->syncRoles(['admin']);
        $this->info('Admin user created: admin@google.com / password');

        // Create employee user
        $employee = User::updateOrCreate(
            ['email' => 'employee@google.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_blocked' => false
            ]
        );
        $employee->syncRoles(['employee']);
        $this->info('Employee user created: employee@google.com / password');

        // Create customer user
        $customer = User::updateOrCreate(
            ['email' => 'customer@google.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_blocked' => false
            ]
        );
        $customer->syncRoles(['customer']);
        $this->info('Customer user created: customer@google.com / password');

        // Add initial credit for the customer
        UserCredit::updateOrCreate(
            ['user_id' => $customer->id],
            ['amount' => 100.00]
        );

        // Create employee-customer relationship
        DB::table('employee_customers')->insertOrIgnore([
            'employee_id' => $employee->id,
            'customer_id' => $customer->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->info('Test users setup completed successfully!');
    }
}
