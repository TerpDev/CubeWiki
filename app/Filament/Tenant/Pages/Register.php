<?php

namespace App\Filament\Tenant\Pages;

use App\Enums\TenantRole;
use App\Models\Tenants;
use App\Models\User;
use Filament\Auth\Events\Registered;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('name')
                    ->label('Your name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->label('Confirm password')
                    ->password()
                    ->required(),
                TextInput::make('tenant_name')
                    ->label('Company / Tenant name')
                    ->required()
                    ->maxLength(255)
                    ->unique('tenants', 'name'),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRegistration(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $tenantName = Arr::get($data, 'tenant_name');
            $userData = Arr::only($data, ['name', 'email', 'password']);

            if (Tenants::where('name', $tenantName)->exists()) {
                throw ValidationException::withMessages([
                    'tenant_name' => 'A tenant with this name already exists. Please choose another name.',
                ]);
            }

            /** @var User $user */
            $user = $this->getUserModel()::create($userData);

            /** @var Tenants $tenant */
            $tenant = Tenants::create(['name' => $tenantName]);

            $user->tenants()->syncWithoutDetaching([
                $tenant->getKey() => ['role' => TenantRole::OWNER->value],
            ]);

            // Optionally create a default API token for the tenant so they can use the API immediately.
            if (! $tenant->tokens()->exists()) {
                $token = $tenant->createToken('default')->plainTextToken;

                Notification::make()
                    ->title('Tenant created')
                    ->body("Save this API token: {$token}")
                    ->success()
                    ->duration(null)
                    ->send();
            }

            return $user;
        });
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // Let the parent handle hashing/password rules; no extra mutation needed here.
        return $data;
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Create your tenant';
    }

    protected function afterRegister(): void
    {
        // Ensure Filament knows the tenant for immediate access after login.
        $tenant = auth()->user()?->tenants()->first();

        if ($tenant) {
            Filament::setTenant($tenant);
        }
    }
}
