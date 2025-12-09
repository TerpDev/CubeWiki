<?php

namespace App\Filament\Tenant\Pages;

use App\Enums\TenantRole;
use App\Models\Tenants;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;

class RegisterTenant extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return __('Register a new tenant!');
    }

    public function mount(): void
    {
        parent::mount();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            TextInput::make('name')->label(__('Name'))->required()->maxLength(255),
        ]);
    }

    protected function handleRegistration(array $data): Tenants
    {

        /** @var Tenants $tenant */
        $tenant = Tenants::create(['name' => $data['name']]);
        auth()->user()
            ->tenants()
            ->syncWithoutDetaching([
                $tenant->id => ['role' => TenantRole::OWNER->value],
            ]);
        $token = $tenant->createToken('default')->plainTextToken;
        Notification::make()
            ->success()
            ->title(__('Tenant created'))
            ->body(__('Tenant created and you have been added to: :name', ['name' => $tenant->name]))
            ->body("Tenant API Token Generated: {$token} - please store it securely as it will not be shown again.")
            ->send();

        return $tenant;
    }


}
