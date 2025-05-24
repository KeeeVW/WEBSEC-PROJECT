<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddSupportPermissions extends Migration
{
    public function up()
    {
        // Create support role if it doesn't exist
        $supportRole = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            'view_tickets',
            'create_tickets',
            'respond_tickets',
            'view_orders'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to support role
        $supportRole->givePermissionTo($permissions);
    }

    public function down()
    {
        // Remove permissions from support role
        $supportRole = Role::where('name', 'support')->first();
        if ($supportRole) {
            $supportRole->revokePermissionTo([
                'view_tickets',
                'create_tickets',
                'respond_tickets',
                'view_orders'
            ]);
        }
    }
} 