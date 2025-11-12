<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class NavigationController extends Controller
{
    public function byToken(string $token, Request $request)
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if (!$tokenModel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        $tenant = $tokenModel->tokenable;

        if (!$tenant || !($tenant instanceof Tenants)) {
            return response()->json([
                'success' => false,
                'message' => 'Token not linked to a tenant'
            ], 401)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        // Get navigation data with content
        $applicationsQuery = $tenant->applications();

        // Filter by application_id if provided
        if ($applicationId = $request->query('application_id')) {
            $applicationsQuery->where('id', $applicationId);
        }

        $applications = $applicationsQuery
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
            'success' => true,
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
            ],
            'applications' => $applications,
        ])
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
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
