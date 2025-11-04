<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('John Doe')
                    ->columnSpan(1),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('john@example.com')
                    ->columnSpan(1),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->placeholder('Enter password')
                    ->helperText('Minimal length: 8 characters.')
                    ->columnSpan(1),


                // Assign tenants to the user (many-to-many)
                MultiSelect::make('tenants')
                    ->label('Tenants')
                    ->relationship('tenants', 'name')
                    ->preload()
                    ->saveRelationshipsUsing(function ($component, $state) {
                        $component->getRecord()->tenants()->sync($state ?? []);
                    })
                    ->helperText('Select one or more tenants to attach to this user.')
                    ->columnSpan(2),
            ]);
    }
}
