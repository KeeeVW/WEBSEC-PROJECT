<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = ['admin', 'manager', 'support', 'customer', 'employee'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_reports',
            
            // Manager permissions
            'manage_orders',
            'manage_inventory',
            'view_sales',
            'manage_employees',
            
            // Support permissions
            'view_tickets',
            'manage_tickets',
            'view_orders',
            'manage_orders',
            
            // Customer permissions
            'place_orders',
            'view_own_orders',
            'create_tickets',
            
            // Employee permissions
            'view_own_customers',
            'manage_own_customers',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::findByName('manager');
        $managerRole->givePermissionTo([
            'manage_orders',
            'manage_inventory',
            'view_sales',
            'manage_employees',
            'view_tickets',
            'view_own_orders',
        ]);

        $supportRole = Role::findByName('support');
        $supportRole->givePermissionTo([
            'view_tickets',
            'manage_tickets',
            'view_orders',
            'manage_orders',
        ]);

        $customerRole = Role::findByName('customer');
        $customerRole->givePermissionTo([
            'place_orders',
            'view_own_orders',
            'create_tickets',
        ]);

        $employeeRole = Role::findByName('employee');
        $employeeRole->givePermissionTo([
            'view_own_customers',
            'manage_own_customers',
            'view_own_orders',
        ]);

        // Create users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@google.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@google.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'name' => 'Support User',
                'email' => 'support@google.com',
                'password' => Hash::make('password'),
                'role' => 'support',
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@google.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Employee User',
                'email' => 'employee@google.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);
        }
    }
}
