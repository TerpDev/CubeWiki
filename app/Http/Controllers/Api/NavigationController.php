<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class NavigationController extends Controller
{
    public function byToken(string $token)
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if (!$tokenModel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $tenant = $tokenModel->tokenable;

        if (!$tenant || !($tenant instanceof Tenants)) {
            return response()->json([
                'success' => false,
                'message' => 'Token not linked to a tenant'
            ], 401);
        }

        // Get navigation data with content
        $applications = $tenant->applications()
            ->with(['categories' => function ($categoryQuery) {
                $categoryQuery->select('id', 'tenant_id', 'application_id', 'name', 'slug')
                    ->with(['pages' => function ($pagesQuery) {
                        $pagesQuery->select('id', 'category_id', 'tenant_id', 'title', 'slug', 'content')
                            ->orderBy('title');
                    }])
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'tenant_id', 'name', 'slug']);

        return response()->json([
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
            ],
            'applications' => $applications,
        ]);
    }


    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->applications();

        if ($q = $request->query('q')) {
            $query->whereLike('slug', $q);
        }

        $applications = $query
            ->with(['categories' => function ($categoryQuery) use ($request) {
                $categoryQuery->select('id', 'tenant_id', 'application_id', 'name', 'slug')
                      ->with(['pages' => function ($pagesQuery) {
                          $pagesQuery->select('id', 'category_id', 'tenant_id', 'title', 'slug')
                                     ->orderBy('title');
                      }])
                      ->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'tenant_id', 'name', 'slug']);

        return response()->json(['data' => $applications]);
    }
}
