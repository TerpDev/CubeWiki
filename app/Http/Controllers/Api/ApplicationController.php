<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /**
     * List applications for a tenant.
     */
    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->applications()->withCount('categories');

        if ($q = $request->query('q')) {
            $query->where('name', 'like', "%{$q}%");
        }

        $apps = $query->orderBy('name')->get();

        return response()->json(['data' => $apps]);
    }

    public function show(Tenants $tenant, string $slug)
    {
        $app = $tenant->applications()->withCount('categories')->where('slug', $slug)->firstOrFail();

        return response()->json(['data' => $app]);
    }}
