<?php

namespace App\Filament\Admin\Resources\Tenants\Pages;

use App\Filament\Admin\Resources\Tenants\TenantsResource;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Laravel\Sanctum\PersonalAccessToken;

class TestApiToken extends Page
{
    protected static string $resource = TenantsResource::class;
    protected string $view = 'filament.admin.resources.tenants.pages.test-api-token';
    protected static ?string $navigationLabel = 'Test API Token';
    protected static ?string $title = 'Test API Token';

    public ?string $token = '';
    public ?array $tenantData = null;

    public function testToken(): void
    {
        // Simple validation
        if (empty($this->token)) {
            Notification::make()
                ->danger()
                ->title('Token vereist')
                ->body('Vul een API token in.')
                ->send();
            return;
        }

        try {
            // Find token in database
            $tokenModel = PersonalAccessToken::findToken($this->token);

            if (!$tokenModel) {
                Notification::make()
                    ->danger()
                    ->title('Token niet gevonden')
                    ->body('Deze token bestaat niet in de database.')
                    ->send();
                return;
            }

            // Get the tenant from the token
            $tenant = $tokenModel->tokenable;

            if (!$tenant || !($tenant instanceof \App\Models\Tenants)) {
                Notification::make()
                    ->danger()
                    ->title('Geen tenant gevonden')
                    ->body('Deze token is niet gekoppeld aan een tenant.')
                    ->send();
                return;
            }

            // Get all tenant data
            $this->tenantData = [
                'tenant' => [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'slug' => $tenant->slug,
                ],
                'applications' => $tenant->applications()->get()->toArray(),
                'categories' => $tenant->categories()->get()->toArray(),
                'pages' => $tenant->pages()->get()->toArray(),
                'statistics' => [
                    'total_applications' => $tenant->applications()->count(),
                    'total_categories' => $tenant->categories()->count(),
                    'total_pages' => $tenant->pages()->count(),
                ],
            ];

            Notification::make()
                ->success()
                ->title('Token werkt!')
                ->body("Data opgehaald voor tenant: {$tenant->name}")
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body($e->getMessage())
                ->send();
        }
    }
}
