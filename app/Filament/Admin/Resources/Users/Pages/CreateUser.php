<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        /** @var \App\Models\User $user */
        $user = $this->record;

        // Sync tenants if provided in the form state
        $tenants = Arr::get($this->form->getState(), 'tenants', []);
        if (! empty($tenants)) {
            $user->tenants()->sync($tenants);
        }

        // Note: API tokens are now created per tenant, not per user
        Notification::make()
            ->title('User Created Successfully')
            ->body('User created. API tokens should be created per tenant.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
