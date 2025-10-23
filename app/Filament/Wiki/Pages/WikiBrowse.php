<?php

namespace App\Filament\Wiki\Pages;

use App\Models\Tenants;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Filament\Facades\Filament;

class WikiBrowse extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Filament Wiki';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.wiki.pages.wiki-browse';

    public $applications = [];
    public $selectedApplication = null;
    public $selectedCategory = null;
    public $selectedPage = null;
    public $pageContent = null;
    public $searchQuery = '';
    public $expandedCategories = [];

    public function mount(): void
    {
        $this->loadNavigation();

        if (request()->has('page')) {
            $pageId = request()->get('page');
            $this->loadPageById($pageId);
        }
    }

    public function updated($property)
    {
        if ($property === 'searchQuery') {
            $this->search();
        }
    }

    public function loadNavigation(): void
    {
        $tenant = Filament::getTenant();

        if (!$tenant) {
            return;
        }

        $response = Http::get(url("/api/tenants/{$tenant->slug}/navigation"));

        if ($response->successful()) {
            $this->applications = $response->json('data', []);
        }
    }

    public function loadPageById($pageId): void
    {
        $tenant = Filament::getTenant();

        if (!$tenant) {
            return;
        }

        $response = Http::get(url("/api/tenants/{$tenant->slug}/pages/{$pageId}"));

        if ($response->successful()) {
            $page = $response->json('data');

            if ($page) {
                $this->selectedApplication = $page['category']['application_id'] ?? null;
                $this->selectedCategory = $page['category_id'] ?? null;
                $this->selectedPage = $page['id'] ?? null;

                // Convert nested arrays to objects recursively
                $this->pageContent = json_decode(json_encode($page));

                // Convert date strings to Carbon instances AFTER json conversion
                if (isset($this->pageContent->updated_at)) {
                    $this->pageContent->updated_at = \Carbon\Carbon::parse($this->pageContent->updated_at);
                }
                if (isset($this->pageContent->created_at)) {
                    $this->pageContent->created_at = \Carbon\Carbon::parse($this->pageContent->created_at);
                }
            }
        }
    }

    public function selectPage($applicationId, $categoryId, $pageId): void
    {
        $this->loadPageById($pageId);

        $this->selectedApplication = $applicationId;
        $this->selectedCategory = $categoryId;
        $this->selectedPage = $pageId;

        if (!in_array($categoryId, $this->expandedCategories)) {
            $this->expandedCategories[] = $categoryId;
        }
    }

    public function toggleCategory($categoryId): void
    {
        if (in_array($categoryId, $this->expandedCategories)) {
            $this->expandedCategories = array_filter($this->expandedCategories, fn($id) => $id !== $categoryId);
        } else {
            $this->expandedCategories[] = $categoryId;
        }
    }
}
