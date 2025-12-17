<?php

namespace App\Providers\Filament;

use App\Filament\Tenant\Pages\EditTenantProfile;
use App\Filament\Tenant\Pages\RegisterTenant;
use App\Filament\Tenant\Pages\Register;
use App\Filament\Tenant\Pages\Dashboard;
use App\Models\Tenants;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tenant')
            ->path('tenant')
            ->login()
            ->registration(Register::class)
            ->tenant(Tenants::class, ownershipRelationship: 'tenants')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->searchableTenantMenu()
            ->brandLogo(asset('images/cubezwart.png'))
            ->darkModeBrandLogo(asset('images/cubewit.png'))
            ->brandLogoHeight('2rem')
            ->tenantRegistration(RegisterTenant::class)
            ->tenantProfile(EditTenantProfile::class)

            ->userMenuItems([
                'member-panel' => MenuItem::make()
                    ->label('Member Panel')
                    ->icon('heroicon-o-user-group')
                    ->url(fn (): string => route('filament.member.pages.dash-board', ['tenant' => Filament::getTenant()?->slug]))
            ])

            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\Filament\Tenant\Resources')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\Filament\Tenant\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\Filament\Tenant\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
            ]);
    }
}
