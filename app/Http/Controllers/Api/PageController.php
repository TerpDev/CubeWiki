<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->pages()->with('category.application');

        if ($q = $request->query('q')) {
            $query->whereLike('slug', $q);
        }

        // Filter by category_id
        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
            $data = $query->orderBy('slug')->get();
        }

        return response()->json(['data' => $data]);
    }

    public function show(Tenants $tenant, Page $page)
    {
        if ($page->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['data' => $page->load('category.application')]);
    }
}
