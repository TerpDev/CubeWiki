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
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                // LEFT SIDE — big RichEditor (takes 2/3 of the width)
                RichEditor::make('content')
                    ->label(__('Content'))
                    ->required()
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ]),

                // RIGHT SIDE — small form fields (takes 1/3 of the width)
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
                            ->label(__('Slug'))
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
            ]);
    }
}
