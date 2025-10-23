<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Tenants;

class WikiPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('wiki')
            ->path('wiki')
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->viteTheme('resources/css/filament/wiki/theme.css')
            //fullscreen
                ->maxContentWidth("full")
            ->tenant(Tenants::class)
            ->login()
            ->registration()
            ->discoverResources(in: app_path('Filament/Wiki/Resources'), for: 'App\Filament\Wiki\Resources')
            ->discoverPages(in: app_path('Filament/Wiki/Pages'), for: 'App\Filament\Wiki\Pages')
            ->discoverWidgets(in: app_path('Filament/Wiki/Widgets'), for: 'App\Filament\Wiki\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->brandName('Cube Wiki');
    }
}
