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
                    ->label(__('Name'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('slug', \Illuminate\Support\Str::slug((string) $state));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->helperText(__('Slug is automatically created.')),
                Select::make('application_id')
                    ->label(__('Application'))
                    ->relationship('application', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText(__('Select an application for this category.')),
            ]);
    }
}
