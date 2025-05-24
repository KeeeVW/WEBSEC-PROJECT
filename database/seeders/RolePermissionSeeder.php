<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin role gets all permissions
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        // Manager role permissions
        $managerRole = Role::findByName('manager');
        $managerRole->givePermissionTo([
            'add_products',
            'edit_products',
            'delete_products',
            'view_products',
            'manage_products',
            'manage_orders',
            'view_reports',
            'manage_inventory'
        ]);

        // Employee role permissions
        $employeeRole = Role::findByName('employee');
        $employeeRole->givePermissionTo([
            'view_products',
            'view_orders',
            'view_tickets',
            'respond_tickets'
        ]);

        // Support role permissions
        $supportRole = Role::findByName('support');
        $supportRole->givePermissionTo([
            'view_tickets',
            'respond_tickets',
            'view_orders'
        ]);

        // Customer role permissions
        $customerRole = Role::findByName('customer');
        $customerRole->givePermissionTo([
            'view_products',
            'view_orders'
        ]);
    }
} 