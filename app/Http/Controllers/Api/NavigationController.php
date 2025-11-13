<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Tenants;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class NavigationController extends Controller
{
    public function byToken(string $token, Request $request)
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        $tenant = $tokenModel->tokenable;

        $applicationsQuery = $tenant->applications();

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

        // Transform pages to include parsed markdown
        $applications->each(function ($application) {
            $application->categories->each(function ($category) {
                $category->pages->transform(function ($page) {
                    return [
                        'id' => $page->id,
                        'category_id' => $page->category_id,
                        'tenant_id' => $page->tenant_id,
                        'title' => $page->title,
                        'slug' => $page->slug,
//                        'content' => $page->content,
                        'content_html' => str($page->content)->markdown()->sanitizeHtml()->toString(),
                    ];
                });
            });
        });

        return response()->json([
            'success' => true,
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
            ],
            'applications' => $applications,
        ]);
//        ->header('Access-Control-Allow-Origin', '*')
//        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
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
