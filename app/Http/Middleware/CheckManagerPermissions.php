<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckManagerPermissions
{
    public function handle(Request $request, Closure $next, $permission = null)
    {
        if (!$request->user() || !$request->user()->hasRole('manager')) {
            abort(403, 'Unauthorized action.');
        }

        // Map route permissions to actual permission names
        $permissionMap = [
            'orders' => ['manage_orders', 'view_orders'],
            'inventory' => ['manage_inventory', 'view_inventory'],
            'reports' => ['reports', 'view_reports'],
            'customers' => ['manage_customers']
        ];

        if ($permission) {
            $requiredPermissions = $permissionMap[$permission] ?? [$permission];
            $hasPermission = false;

            foreach ($requiredPermissions as $perm) {
                if ($request->user()->hasPermissionTo($perm)) {
                    $hasPermission = true;
                    break;
                }
            }

            if (!$hasPermission) {
                abort(403, 'You do not have permission to access this resource.');
            }
        }

        return $next($request);
    }
} 