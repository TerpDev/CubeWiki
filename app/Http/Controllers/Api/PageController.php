<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // List pages for a tenant (simple: no pagination)
    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->pages()->with('category');

        if ($q = $request->query('q')) {
            $query->whereLike('title', $q);
        }

        $data = $query->orderBy('title')->get();

        return response()->json(['data' => $data]);
    }

    // Show a single page (tenant scoped)
    public function show(Tenants $tenant, Page $page)
    {
        if ($page->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['data' => $page->load('category.application')]);
    }
}
