<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create admin_users permission if it doesn't exist
        $adminUsersPermission = Permission::firstOrCreate(['name' => 'admin_users']);

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign admin_users permission to admin role
        $adminRole->givePermissionTo($adminUsersPermission);

        // Create admin permissions
        $adminPermissions = [
            'manage_roles',
            'manage_permissions',
            'view_reports',
            'manage_settings',
            'edit_users',
            'show_users',
            'delete_users',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to the admin role
        $adminRole->givePermissionTo($adminPermissions);
    }
} 