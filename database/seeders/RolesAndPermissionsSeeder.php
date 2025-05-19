<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            ['name' => 'view_users', 'display_name' => 'View Users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users'],
            ['name' => 'admin_users', 'display_name' => 'Administer Users'],
            
            // Product management
            ['name' => 'view_products', 'display_name' => 'View Products'],
            ['name' => 'add_products', 'display_name' => 'Add Products'],
            ['name' => 'edit_products', 'display_name' => 'Edit Products'],
            ['name' => 'delete_products', 'display_name' => 'Delete Products'],
            
            // Credit management
            ['name' => 'view_credits', 'display_name' => 'View Credits'],
            ['name' => 'add_credits', 'display_name' => 'Add Credits'],
            
            // Customer management
            ['name' => 'manage_customers', 'display_name' => 'Manage Customers'],
            
            // Purchase management
            ['name' => 'purchase_products', 'display_name' => 'Purchase Products'],
            ['name' => 'view_purchases', 'display_name' => 'View Purchases'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles and assign permissions
        
        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        // Employee role
        $employeeRole = Role::create(['name' => 'employee']);
        $employeeRole->givePermissionTo([
            'view_users',
            'view_products',
            'add_products',
            'edit_products',
            'delete_products',
            'view_credits',
            'add_credits',
            'manage_customers',
        ]);
        
        // Customer role
        $customerRole = Role::create(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view_products',
            'purchase_products',
            'view_credits',
            'view_purchases',
        ]);
        
        $this->command->info('Roles and permissions created successfully');
    }
} 