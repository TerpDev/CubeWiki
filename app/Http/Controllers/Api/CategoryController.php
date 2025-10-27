<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List categories for a tenant
    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->categories()->withCount('pages')->with('application');

        if ($q = $request->query('q')) {
            $query->whereLike('slug', $q);
        }

        $data = $query->orderBy('slug')->get();

        return response()->json(['data' => $data]);
    }

    // Show a single category (tenant scoped)
    public function show(Tenants $tenant, Category $category)
    {
        if ($category->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['data' => $category->load(['pages', 'application'])]);
    }
}
