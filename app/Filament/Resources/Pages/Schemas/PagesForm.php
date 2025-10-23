<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Category;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make()
                    ->columns([
                        'default' => 1,
                        'lg' => 3,
                    ])
                    ->schema([
                        // LEFT SIDE — big RichEditor
                        RichEditor::make('content')
                            ->label('Content')
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 2,
                            ]),

                        // RIGHT SIDE — small form fields
                        Grid::make()
                            ->columns(1)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 1,
                            ])
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('Title'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', \Illuminate\Support\Str::slug((string) $state));
                                    }),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->disabled()
                                    ->helperText(__('Slug is automatically created.')),

                                Select::make('category_id')
                                    ->label(__('Category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $tenantId = null;
                                        if ($state) {
                                            $category = Category::find($state);
                                            $tenantId = $category?->tenant_id;
                                        }
                                        $set('tenant_id', $tenantId);
                                    }),

                            ]),
                    ]),
            ]);
    }
}
