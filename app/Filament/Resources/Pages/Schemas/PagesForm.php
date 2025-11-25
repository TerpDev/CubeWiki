<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Category;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                MarkdownEditor::make('content')

                    ->label('Content')
                    ->required()
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 9,
                    ]),

                Grid::make(1)
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 3,
                    ])
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set): void {
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
                            ->afterStateUpdated(function ($state, callable $set): void {
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
