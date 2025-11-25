<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                // Show a plain-text preview of the content (strip HTML, no word limit)
                TextColumn::make('content')
                    ->label('Content')
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return '—';
                        }

                        // HTML verwijderen en tekst normaliseren
                        $text = strip_tags($state);
                        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $text = preg_replace('/\s+/', ' ', trim($text));

                        // Beperk tot 10 woorden
                        $words = explode(' ', $text);
                        if (count($words) > 10) {
                            $text = implode(' ', array_slice($words, 0, 10)).'...';
                        }

                        return $text;
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('Created'))
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
            ]);
    }
}
