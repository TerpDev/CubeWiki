<?php
namespace App\Filament\Pages;

use App\Models\Page;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Tenants;
use App\Models\User;

/**
 * @property-read Tenants|null $tenant
 */
class Dashboard extends BaseDashboard
{
    /**
     * @var string|\BackedEnum|null
     */
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        /** @var Tenants|null $tenant */
        $tenant = Filament::getTenant();
        /** @var User|null $user */
        $user = auth()->user();

        return [
            'tenant' => $tenant,
            'name' => $user?->name,
            'applicationsCount' => $tenant ? $tenant->applications()->count() : 0,
            'categoriesCount' => $tenant ? $tenant->categories()->count() : 0,
            'pagesCount' => $tenant ? Page::where('tenant_id', $tenant->id)->count() : 0,
        ];
    }

    public function getWidgets(): array
    {
        return [];
    }
}
