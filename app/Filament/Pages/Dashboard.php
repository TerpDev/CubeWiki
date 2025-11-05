<?php
namespace App\Filament\Pages;

use App\Models\Page;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class DashBoard extends BaseDashboard
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    public function getViewData(): array
    {
        $tenant = Filament::getTenant();
        $user = auth()->user();

        return [
            'tenant' => $tenant,
            'name' => $user?->name,
            'applicationsCount' => $tenant?->applications()->count() ?? 0,
            'categoriesCount' => $tenant?->categories()->count() ?? 0,
            'pagesCount' => Page::where('tenant_id', $tenant?->id)->count() ?? 0,
        ];
    }

    public function getWidgets(): array
    {
        return [];
    }
}
