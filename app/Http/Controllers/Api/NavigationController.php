<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
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
