<?php

namespace App\Filament\Admin\Resources\Tenants\Pages;

use App\Filament\Admin\Resources\Tenants\TenantsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

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
                ->action(function () {
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
