<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantTokenMatch
{
    /**
     * Ensure the authenticated token belongs to the requested tenant
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $tenant = $request->route('tenant');

        // If authenticated via Sanctum token, check if the token belongs to the tenant
        if ($user && $tenant) {
            // The token's tokenable should be the tenant
            // If user is actually a Tenants model (token authenticated tenant), ensure ids match
            if ($user instanceof \App\Models\Tenants) {
                if ($user->id !== $tenant->id) {
                    return response()->json([
                        'message' => 'This API token does not have access to this tenant.',
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}
