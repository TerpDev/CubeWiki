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

        if (! $tokenModel) {
            return response()->json(['success' => false, 'message' => 'Invalid token.'], 404);
        }

        /** @var \App\Models\Tenants $tenant */
        $tenant = $tokenModel->tokenable;

        $applicationsQuery = $tenant->applications();

        if ($applicationId = $request->query('application_id')) {
            $applicationsQuery->where('id', $applicationId);
        }

        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Application[] $applications */
        $applications = $applicationsQuery
            ->with(['categories' => function ($categoryQuery): void {
                $categoryQuery->select('id', 'tenant_id', 'application_id', 'name', 'slug')
                    ->with(['pages' => function ($pagesQuery): void {
                        $pagesQuery->select('id', 'category_id', 'tenant_id', 'title', 'slug', 'content')
                            ->orderBy('title');
                    }])
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'tenant_id', 'name', 'slug']);

        // Transform pages to include parsed markdown
        $applications->each(function ($application): void {
            /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories */
            $categories = $application->getRelation('categories') ?? collect();

            $categories->each(function ($category): void {
                /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Page[] $pages */
                $pages = $category->getRelation('pages') ?? collect();

                $transformed = $pages->transform(function ($page) {
                    return [
                        'id' => $page->id,
                        'category_id' => $page->category_id,
                        'tenant_id' => $page->tenant_id,
                        'title' => $page->title,
                        'slug' => $page->slug,
                        'content_html' => str($page->content)->markdown()->sanitizeHtml()->toString(),
                    ];
                });

                // replace the relation on the category with the transformed collection/array
                $category->setRelation('pages', $transformed);
            });

            // ensure the application keeps the (possibly modified) categories relation
            $application->setRelation('categories', $categories);
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
    }

    public function index(Tenants $tenant, Request $request)
    {
        $query = $tenant->applications();

        if ($q = $request->query('q')) {
            $query->whereLike('slug', $q);
        }

        $applications = $query
            ->with(['categories' => function ($categoryQuery): void {
                $categoryQuery->select('id', 'tenant_id', 'application_id', 'name', 'slug')
                    ->with(['pages' => function ($pagesQuery): void {
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
