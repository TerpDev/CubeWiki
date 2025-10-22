<?php

namespace App\Filament\Admin\Resources\Tenants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->sortable(),

                TextColumn::make('applications_count')
                    ->label('Applications')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $apps = $record->relationLoaded('applications') ? $record->applications : $record->applications()->get();

                        $count = $apps->count();

                        if ($count === 0) {
                            return '<span class="text-sm text-gray-500">0</span>';
                        }

                        $items = '';
                        foreach ($apps as $app) {
                            $items .= '<li class="px-2 py-1 text-sm">- ' . e($app->name) . '</li>';
                        }

                        $html = '<details class="bg-white rounded border p-1">'
                            . '<summary class="text-sm font-medium cursor-pointer">' . e($count . ' applications') . '</summary>'
                            . '<ul class="mt-2">' . $items . '</ul>'
                            . '</details>';

                        return $html;
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                CreateAction::make()->visible(true),

            ]);
    }
}
