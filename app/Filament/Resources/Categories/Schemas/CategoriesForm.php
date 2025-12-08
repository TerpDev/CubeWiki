<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Application;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->dehydrated(),

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
                    ->relationship(
                        'application',
                        'name',
                        fn ($query) => $query->when(
                            Filament::getTenant(),
                            fn ($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                        )
                    )
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $tenantId = null;

                        if ($state) {
                            $tenantId = Application::find($state)?->tenant_id;
                        }

                        $set('tenant_id', $tenantId);
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText(__('Select an application for this category.')),
            ]);
    }
}
