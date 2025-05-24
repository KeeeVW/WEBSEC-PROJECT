<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add new permissions for manager
        $managerPermissions = [
            'manage_orders',
            'view_orders',
            'create_orders',
            'edit_orders',
            'delete_orders',
            'manage_inventory',
            'view_inventory',
            'add_inventory',
            'edit_inventory',
            'delete_inventory',
            'view_reports',
            'export_reports',
            'view_sales_reports',
            'view_inventory_reports',
            'view_customer_reports'
        ];

        // Insert new permissions
        foreach ($managerPermissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Get manager role
        $managerRole = DB::table('roles')->where('name', 'manager')->first();

        if ($managerRole) {
            // Assign new permissions to manager role
            foreach ($managerPermissions as $permission) {
                $permissionId = DB::table('permissions')->where('name', $permission)->first()->id;
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $managerRole->id
                ]);
            }
        }
    }

    public function down()
    {
        // Get manager role
        $managerRole = DB::table('roles')->where('name', 'manager')->first();

        if ($managerRole) {
            // Remove role-permission assignments
            DB::table('role_has_permissions')
                ->where('role_id', $managerRole->id)
                ->whereIn('permission_id', function($query) {
                    $query->select('id')
                        ->from('permissions')
                        ->whereIn('name', [
                            'manage_orders',
                            'view_orders',
                            'create_orders',
                            'edit_orders',
                            'delete_orders',
                            'manage_inventory',
                            'view_inventory',
                            'add_inventory',
                            'edit_inventory',
                            'delete_inventory',
                            'view_reports',
                            'export_reports',
                            'view_sales_reports',
                            'view_inventory_reports',
                            'view_customer_reports'
                        ]);
                })
                ->delete();
        }

        // Remove the permissions
        DB::table('permissions')
            ->whereIn('name', [
                'manage_orders',
                'view_orders',
                'create_orders',
                'edit_orders',
                'delete_orders',
                'manage_inventory',
                'view_inventory',
                'add_inventory',
                'edit_inventory',
                'delete_inventory',
                'view_reports',
                'export_reports',
                'view_sales_reports',
                'view_inventory_reports',
                'view_customer_reports'
            ])
            ->delete();
    }
}; 