<?php

// app/Filament/Member/Pages/EditTenantProfile.php

namespace App\Filament\Member\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile as BaseEditTenantProfile;

class EditTenantProfile extends BaseEditTenantProfile
{
    public static function getLabel(): string
    {
        return __('Edit tenant profile');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            TextInput::make('name')->label(__('Name'))->required(),
        ]);
    }
}
