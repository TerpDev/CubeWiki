<?php

namespace App\Filament\Admin\Resources\Tenants\Pages;

use App\Filament\Admin\Resources\Tenants\TenantsResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Laravel\Sanctum\PersonalAccessToken;

class TestApiToken extends Page
{
    protected static string $resource = TenantsResource::class;

    protected string $view = 'filament.admin.resources.tenants.pages.test-api-token';

    protected static ?string $navigationLabel = 'Test API Token';

    protected static ?string $title = 'Test API Token';

    public ?string $token = '';

    public ?array $tenantData = null;

    public ?int $selectedApplicationId = null;

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

            if (! $tokenModel) {
                Notification::make()
                    ->danger()
                    ->title('Token niet gevonden')
                    ->body('Deze token bestaat niet in de database.')
                    ->send();

                return;
            }

            // Get the tenant from the token
            $tenant = $tokenModel->tokenable;

            if (! $tenant || ! ($tenant instanceof \App\Models\Tenants)) {
                Notification::make()
                    ->danger()
                    ->title('Geen tenant gevonden')
                    ->body('Deze token is niet gekoppeld aan een tenant.')
                    ->send();

                return;
            }

            // Get all tenant data
            $applicationsQuery = $tenant->applications();

            // Filter by selected application if set
            if ($this->selectedApplicationId) {
                $applicationsQuery->where('id', $this->selectedApplicationId);
            }

            $applications = $applicationsQuery->get();

            $this->tenantData = [
                'tenant' => [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'slug' => $tenant->slug,
                ],
                'all_applications' => $tenant->applications()->get(['id', 'name'])->toArray(), // For dropdown
                'applications' => $applications->toArray(),
                'categories' => $this->selectedApplicationId
                    ? $tenant->categories()->where('application_id', $this->selectedApplicationId)->get()->toArray()
                    : $tenant->categories()->get()->toArray(),
                'pages' => $this->selectedApplicationId
                    ? $tenant->pages()->whereHas('category', function ($q): void {
                        $q->where('application_id', $this->selectedApplicationId);
                    })->get()->toArray()
                    : $tenant->pages()->get()->toArray(),
                'statistics' => [
                    'total_applications' => $this->selectedApplicationId ? 1 : $tenant->applications()->count(),
                    'total_categories' => $this->selectedApplicationId
                        ? $tenant->categories()->where('application_id', $this->selectedApplicationId)->count()
                        : $tenant->categories()->count(),
                    'total_pages' => $this->selectedApplicationId
                        ? $tenant->pages()->whereHas('category', function ($q): void {
                            $q->where('application_id', $this->selectedApplicationId);
                        })->count()
                        : $tenant->pages()->count(),
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

    public function updatedSelectedApplicationId(): void
    {
        // Reload data when application selection changes
        if ($this->token) {
            $this->testToken();
        }
    }
}
