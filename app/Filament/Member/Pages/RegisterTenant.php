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
        return 'Register your tenant';
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            Checkbox::make('join_if_exists')
                ->label('Join tenant if the name already exists')
                ->default(true),
        ]);
    }

    protected function handleRegistration(array $data): Tenants
    {
        $existing = Tenants::where('name', $data['name'])->first();

        if ($existing) {
            if (!empty($data['join_if_exists'])) {
                auth()->user()->tenants()->attach($existing->id);

                Notification::make()
                    ->success()
                    ->title('Joined tenant')
                    ->body('You have been added to: ' . $existing->name)
                    ->send();

                return $existing;
            }

            Notification::make()
                ->danger()
                ->title('Tenant name taken')
                ->body('A tenant with this name already exists. Enable the checkbox if you want to join this tenant, or choose a different name.')
                ->send();

            throw ValidationException::withMessages([
                'name' => 'A tenant with this name already exists. Enable "Join existing tenant if the name already exists" to join it, or choose a different name.',
            ]);
        }

        /** @var Tenants $tenant */
        $tenant = Tenants::create(['name' => $data['name']]);
        auth()->user()->tenants()->attach($tenant->id);

        Notification::make()
            ->success()
            ->title('Tenant created')
            ->body('Tenant created and you have been added to: ' . $tenant->name)
            ->send();

        return $tenant;
    }
}
