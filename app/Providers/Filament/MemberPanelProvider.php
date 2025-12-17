<?php

namespace App\Providers\Filament;

use App\Enums\TenantRole;
use App\Models\Tenants;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
class MemberPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('member')
            ->path('member')
            ->login()
            ->colors(['primary' => Color::Amber])
            ->tenant(Tenants::class, ownershipRelationship: 'tenants')
            ->tenantMenu(true) // hide tenant switcher dropdown for members
            ->brandLogo(asset('images/cubezwart.png'))
            ->darkModeBrandLogo(asset('images/cubewit.png'))
            ->brandLogoHeight('2rem')

            ->userMenuItems([
                'tenant-panel' => MenuItem::make()
                    ->label('Tenant Panel')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (): string => route('filament.tenant.pages.dashboard', ['tenant' => Filament::getTenant()?->slug]))
                    ->visible(fn (): bool => auth()->user()?->roleForTenant(Filament::getTenant()) === TenantRole::OWNER->value)
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->maxContentWidth(Width::Full)

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
            ->authMiddleware([Authenticate::class])
            ->plugin(
                FilamentLanguageSwitcherPlugin::make()
                    ->locales([
                        ['code' => 'en', 'name' => 'English', 'flag' => 'us'],
                        ['code' => 'nl', 'name' => 'Nederlands', 'flag' => 'nl'],
                    ])
            );
    }
}
