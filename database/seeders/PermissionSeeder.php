<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'add_products',
            'edit_products',
            'delete_products',
            'view_products',
            'manage_products',
            'manage_customers',
            'manage_orders',
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_reports',
            'manage_employees',
            'approve_purchases',
            'manage_inventory',
            'view_tickets',
            'respond_tickets',
            'view_orders',
            // Add any other missing permissions here
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
            $this->command->info('Permission ' . $permission . ' created or already exists.');
        }
    }
}
