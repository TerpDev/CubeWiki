<?php

namespace App\Filament\Admin\Resources\Tenants\Pages;

use App\Filament\Admin\Resources\Tenants\TenantsResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTenants extends EditRecord
{
    protected static string $resource = TenantsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_token')
                ->label('Create New API Token')
                ->icon('heroicon-o-key')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Create New API Token')
                ->modalDescription('Create a new API token for this tenant. The token will have access to all applications, categories, and pages of this tenant.')
                ->modalSubmitActionLabel('Create Token')
                ->action(function (): void {
                    /** @var \App\Models\Tenants $tenant */
                    $tenant = $this->record;

                    // Create new token for entire tenant (no specific resource restrictions)
                    $token = $tenant->createToken('admin-created-token')->plainTextToken;

                    Notification::make()
                        ->title('New Token Created')
                        ->body("API Token (copy now): {$token}")
                        ->success()
                        ->duration(null) // Don't auto-hide
                        ->send();
                }),

            DeleteAction::make(),
        ];
    }
}
