<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoriesForm
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
                Select::make('application_id')
                    ->label('Application')
                    ->relationship('application', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->nullable()
                    ->helperText('Select an application for this category (optional)'),

//                Select::make('tenant_id')
//                    ->label('Tenant')
//                    ->relationship('tenant', 'name')
//                    ->searchable()
//                    ->preload()
//                    ->required(),
            ]);
    }
}
