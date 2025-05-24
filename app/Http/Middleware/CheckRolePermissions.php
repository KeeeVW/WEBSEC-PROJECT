<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRolePermissions
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        // If user is manager, check manager-specific permissions
        if ($user->hasRole('manager')) {
            if (in_array('access_manager_dashboard', $permissions)) {
                return $next($request);
            }
        }

        // If user is support, check support-specific permissions
        if ($user->hasRole('support')) {
            if (in_array('access_support_dashboard', $permissions)) {
                return $next($request);
            }
        }

        // If no permissions match, return 403
        abort(403, 'Unauthorized action.');
    }
} 