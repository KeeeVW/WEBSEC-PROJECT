<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SupportPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions if they don't exist
        $permissions = [
            'view_tickets',
            'create_tickets',
            'view_orders',
            'create_orders'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Get the support role
        $supportRole = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'web']);

        // Assign permissions to support role
        $supportRole->givePermissionTo($permissions);
    }
} 