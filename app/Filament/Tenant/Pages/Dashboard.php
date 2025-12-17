<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Home';

    public function getHeading(): string
    {
        // Detect which panel we're currently in
        $panelId = Filament::getCurrentPanel()?->getId();

        // Return appropriate heading based on panel
        return match($panelId) {
            'member' => 'Member Dashboard',
            'tenant' => 'Tenant Dashboard',
            'admin' => 'Admin Dashboard',
            default => 'Dashboard',
        };
    }
}

