<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        // Automatically create an API token for the new user
        $user = $this->record;

        // Sync tenants if provided in the form state
        $tenants = Arr::get($this->form->getState(), 'tenants', []);
        if (!empty($tenants)) {
            $user->tenants()->sync($tenants);
        }

        // Create API token after syncing tenants
        $token = $user->createToken('admin-created-token')->plainTextToken;

        // Show notification with the token
        Notification::make()
            ->title('User Created Successfully')
            ->body("API Token (save this, it won't be shown again): {$token}")
            ->success()
            ->duration(null) // Don't auto-hide so admin can copy it
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
