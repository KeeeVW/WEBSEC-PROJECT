<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserCredit;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        
        // Create permissions
        $permissions = [
            'view_users',
            'edit_users',
            'delete_users',
            'admin_users',
            'manage_credits',
            'manage_products',
            'view_customers'
        ];
        
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
        
        // Assign permissions to roles
        $adminRole->givePermissionTo($permissions);
        $employeeRole->givePermissionTo(['view_users', 'edit_users', 'manage_credits', 'view_customers']);
        
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_blocked' => false
            ]
        );
        $admin->assignRole('admin');
        
        // Create employee user
        $employee = User::firstOrCreate(
            ['email' => 'employee@google.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_blocked' => false
            ]
        );
        $employee->assignRole('employee');
        
        // Create customer user
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_blocked' => false
            ]
        );
        $customer->assignRole('customer');
        
        // Add initial credit for the customer
        UserCredit::firstOrCreate(
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
    }
} 