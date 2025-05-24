<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register all permissions
        $permissions = [
            // Manager permissions
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
            'manage_support_roles',

            // Support permissions
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

        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });
        }
    }
} 