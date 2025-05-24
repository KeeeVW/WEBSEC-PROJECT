<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Additional permissions for Manager
        $managerPermissions = [
            'access_manager_dashboard',
            'manage_support_staff',
            'view_all_tickets',
            'manage_ticket_priorities',
            'manage_ticket_categories',
            'view_system_logs',
            'manage_system_settings',
            'view_financial_reports',
            'manage_pricing',
            'manage_discounts',
            'view_customer_details',
            'manage_customer_credits',
            'manage_product_categories',
            'manage_support_roles'
        ];

        // Additional permissions for Support
        $supportPermissions = [
            'access_support_dashboard',
            'create_tickets',
            'close_tickets',
            'reassign_tickets',
            'view_ticket_history',
            'manage_ticket_attachments',
            'view_customer_history',
            'view_product_details',
            'view_order_details',
            'manage_ticket_notes',
            'view_support_analytics'
        ];

        // Insert new permissions
        foreach (array_merge($managerPermissions, $supportPermissions) as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Get role IDs
        $managerRole = DB::table('roles')->where('name', 'manager')->first();
        $supportRole = DB::table('roles')->where('name', 'support')->first();

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

        if ($supportRole) {
            // Assign new permissions to support role
            foreach ($supportPermissions as $permission) {
                $permissionId = DB::table('permissions')->where('name', $permission)->first()->id;
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $supportRole->id
                ]);
            }
        }
    }

    public function down()
    {
        // Get role IDs
        $managerRole = DB::table('roles')->where('name', 'manager')->first();
        $supportRole = DB::table('roles')->where('name', 'support')->first();

        // Remove role-permission assignments
        if ($managerRole) {
            DB::table('role_has_permissions')
                ->where('role_id', $managerRole->id)
                ->whereIn('permission_id', function($query) {
                    $query->select('id')
                        ->from('permissions')
                        ->whereIn('name', [
                            'access_manager_dashboard',
                            'manage_support_staff',
                            'view_all_tickets',
                            'manage_ticket_priorities',
                            'manage_ticket_categories',
                            'view_system_logs',
                            'manage_system_settings',
                            'view_financial_reports',
                            'manage_pricing',
                            'manage_discounts',
                            'view_customer_details',
                            'manage_customer_credits',
                            'manage_product_categories',
                            'manage_support_roles'
                        ]);
                })
                ->delete();
        }

        if ($supportRole) {
            DB::table('role_has_permissions')
                ->where('role_id', $supportRole->id)
                ->whereIn('permission_id', function($query) {
                    $query->select('id')
                        ->from('permissions')
                        ->whereIn('name', [
                            'access_support_dashboard',
                            'create_tickets',
                            'close_tickets',
                            'reassign_tickets',
                            'view_ticket_history',
                            'manage_ticket_attachments',
                            'view_customer_history',
                            'view_product_details',
                            'view_order_details',
                            'manage_ticket_notes',
                            'view_support_analytics'
                        ]);
                })
                ->delete();
        }

        // Remove the permissions
        DB::table('permissions')
            ->whereIn('name', array_merge(
                [
                    'access_manager_dashboard',
                    'manage_support_staff',
                    'view_all_tickets',
                    'manage_ticket_priorities',
                    'manage_ticket_categories',
                    'view_system_logs',
                    'manage_system_settings',
                    'view_financial_reports',
                    'manage_pricing',
                    'manage_discounts',
                    'view_customer_details',
                    'manage_customer_credits',
                    'manage_product_categories',
                    'manage_support_roles'
                ],
                [
                    'access_support_dashboard',
                    'create_tickets',
                    'close_tickets',
                    'reassign_tickets',
                    'view_ticket_history',
                    'manage_ticket_attachments',
                    'view_customer_history',
                    'view_product_details',
                    'view_order_details',
                    'manage_ticket_notes',
                    'view_support_analytics'
                ]
            ))
            ->delete();
    }
}; 