<?php

namespace App\Filament\Member\Pages;

use App\Models\Tenants;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class RegisterTenant extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return __('Register tenant');
    }

    public function mount(): void
    {
        parent::mount();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            TextInput::make('name')->label(__('Name'))->required()->maxLength(255),
            Checkbox::make('join_if_exists')
                ->label(__('Join tenant if the name already exists'))
                ->default(true),
        ]);
    }

    protected function handleRegistration(array $data): Tenants
    {
        $existing = Tenants::where('name', $data['name'])->first();

        if ($existing) {
            if (!empty($data['join_if_exists'])) {
                auth()->user()->tenants()->attach($existing->id);

                // Create token for existing tenant
                $this->ensureTokenForTenantAndNotify($existing);

                Notification::make()
                    ->success()
                    ->title(__('Joined tenant'))
                    ->body(__('You have been added to: :name', ['name' => $existing->name]))
                    ->send();

                return $existing;
            }

            Notification::make()
                ->danger()
                ->title(__('Tenant name taken'))
                ->body(__('A tenant with this name already exists. Enable the checkbox if you want to join this tenant, or choose a different name.'))
                ->send();

            throw ValidationException::withMessages([
                'name' => __('A tenant with this name already exists. Enable "Join existing tenant if the name already exists" to join it, or choose a different name.'),
            ]);
        }

        /** @var Tenants $tenant */
        $tenant = Tenants::create(['name' => $data['name']]);
        auth()->user()->tenants()->attach($tenant->id);

        // Create token for new tenant
        $this->ensureTokenForTenantAndNotify($tenant);

        Notification::make()
            ->success()
            ->title(__('Tenant created'))
            ->body(__('Tenant created and you have been added to: :name', ['name' => $tenant->name]))
            ->send();

        return $tenant;
    }

    private function ensureTokenForTenantAndNotify(Tenants $tenant): void
    {
        // Create token for tenant if it doesn't have one
        if (!$tenant->tokens()->exists()) {
            $token = $tenant->createToken('default')->plainTextToken;

            Notification::make()
                ->title('API Token created for tenant')
                ->body("Tenant: {$tenant->name} - Token: {$token}")
                ->success()
                ->duration(null) // Don't auto-hide so it can be copied
                ->send();
        }
    }
}

