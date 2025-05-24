<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up()
    {
        // Create permissions if they don't exist
        $permissions = [
            'reports',
            'manage_orders',
            'view_orders',
            'manage_inventory',
            'view_inventory',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get the manager role
        $managerRole = Role::where('name', 'manager')->first();

        if ($managerRole) {
            // Assign all permissions to the manager role
            $managerRole->givePermissionTo($permissions);
        }
    }

    public function down()
    {
        // Get the manager role
        $managerRole = Role::where('name', 'manager')->first();

        if ($managerRole) {
            // Revoke all permissions from the manager role
            $managerRole->revokePermissionTo([
                'reports',
                'manage_orders',
                'view_orders',
                'manage_inventory',
                'view_inventory',
                'view_reports'
            ]);
        }
    }
}; 