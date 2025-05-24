<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add Manager role
        DB::table('roles')->insert([
            'name' => 'manager',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Add Support role
        DB::table('roles')->insert([
            'name' => 'support',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Add permissions for Manager
        $managerPermissions = [
            'view_reports',
            'manage_employees',
            'approve_purchases',
            'manage_inventory'
        ];

        foreach ($managerPermissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Add permissions for Support
        $supportPermissions = [
            'view_tickets',
            'respond_tickets',
            'view_customers',
            'view_orders'
        ];

        foreach ($supportPermissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Assign permissions to roles
        $managerRole = DB::table('roles')->where('name', 'manager')->first();
        $supportRole = DB::table('roles')->where('name', 'support')->first();

        foreach ($managerPermissions as $permission) {
            $permissionId = DB::table('permissions')->where('name', $permission)->first()->id;
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $managerRole->id
            ]);
        }

        foreach ($supportPermissions as $permission) {
            $permissionId = DB::table('permissions')->where('name', $permission)->first()->id;
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $supportRole->id
            ]);
        }
    }

    public function down()
    {
        // Remove role-permission assignments
        $managerRole = DB::table('roles')->where('name', 'manager')->first();
        $supportRole = DB::table('roles')->where('name', 'support')->first();

        if ($managerRole) {
            DB::table('role_has_permissions')->where('role_id', $managerRole->id)->delete();
        }
        if ($supportRole) {
            DB::table('role_has_permissions')->where('role_id', $supportRole->id)->delete();
        }

        // Remove roles
        DB::table('roles')->where('name', 'manager')->delete();
        DB::table('roles')->where('name', 'support')->delete();

        // Remove permissions
        $permissions = array_merge(
            ['view_reports', 'manage_employees', 'approve_purchases', 'manage_inventory'],
            ['view_tickets', 'respond_tickets', 'view_customers', 'view_orders']
        );
        DB::table('permissions')->whereIn('name', $permissions)->delete();
    }
}; 