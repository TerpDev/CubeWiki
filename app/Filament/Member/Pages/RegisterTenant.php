<?php
// app/Filament/Member/Pages/RegisterTenant.php
namespace App\Filament\Member\Pages;

use App\Models\Tenants;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;

class RegisterTenant extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register your company';
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            TextInput::make('name')->label('Name')->required()->maxLength(255),
        ]);
    }

    protected function handleRegistration(array $data): Tenants
    {
        /** @var Tenants $tenant */
        $tenant = Tenants::create(['name' => $data['name']]);

        auth()->user()->tenants()->attach($tenant->id);

        return $tenant;
    }
}
