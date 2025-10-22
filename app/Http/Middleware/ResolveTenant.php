<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenants;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // Resolve the current tenant based on the user's assigned tenants
            $currentTenant = $user->tenants()->first(); // Adjust logic if needed

            if ($currentTenant) {
                // Share the tenant globally (e.g., for scoping queries)
                app()->instance('currentTenant', $currentTenant);
            }
        }

        return $next($request);
    }
}
