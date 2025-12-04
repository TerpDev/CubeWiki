<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tenants;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->categories()->withCount('pages')->with('application');

        if ($q = $request->query('q')) {
            $query->whereLike('slug', $q);
        }

        // Filter by application_id
        if ($applicationId = $request->query('application_id')) {
            $query->where('application_id', $applicationId);
        }

        $data = $query->orderBy('slug')->get();

        return response()->json(['data' => $data]);
    }

    public function show(Tenants $tenant, Category $category)
    {
        if ($category->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['data' => $category->load(['pages', 'application'])]);
    }
}
