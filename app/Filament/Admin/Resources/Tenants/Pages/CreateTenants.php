<?php

namespace App\Filament\Admin\Resources\Tenants\Pages;

use App\Filament\Admin\Resources\Tenants\TenantsResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTenants extends CreateRecord
{
    protected static string $resource = TenantsResource::class;

    protected function afterCreate(): void
    {
        $tenant = $this->record;

        // Create API token for the new tenant
        $token = $tenant->createToken('admin-created-token')->plainTextToken;

        // Show notification with the token
        Notification::make()
            ->title('Tenant Created Successfully')
            ->body("API Token (save this, it won't be shown again): {$token}")
            ->success()
            ->duration(null) // Don't auto-hide so admin can copy it
            ->send();
    }
}
