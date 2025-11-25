<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->applications()->withCount('categories');

        if ($q = $request->query('q')) {
            $query->whereLike('slug', $q);
        }

        $apps = $query->orderBy('slug')->get();

        return response()->json(['data' => $apps]);
    }

    public function show(Tenants $tenant, string $slug)
    {
        $app = $tenant->applications()->withCount('categories')->where('slug', $slug)->firstOrFail();

        return response()->json(['data' => $app]);
    }
}
