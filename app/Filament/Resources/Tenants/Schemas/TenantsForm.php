<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class TenantsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // auto-generate a slug client-side for immediate feedback
                        $set('slug', \Illuminate\Support\Str::slug((string) $state));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->helperText('Slug is automatically created.'),
            ]);
    }
}
