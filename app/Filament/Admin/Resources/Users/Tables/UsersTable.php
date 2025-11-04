<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('tokens_count')
                    ->label('API Tokens')
                    ->counts('tokens')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('tenants_count')
                    ->label('Tenants')
                    ->counts('tenants')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('create_token')
                    ->label('Create Token')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Create API Token')
                    ->modalDescription(fn ($record) => "Create a new API token for {$record->name}?")
                    ->modalSubmitActionLabel('Create Token')
                    ->action(function ($record) {
                        // Delete old tokens
                        $record->tokens()->delete();

                        // Create new token
                        $token = $record->createToken('admin-created-token')->plainTextToken;

                        Notification::make()
                            ->title('Token Created')
                            ->body("Token: {$token}")
                            ->success()
                            ->duration(null) // Don't auto-hide
                            ->send();
                    }),
                DeleteBulkAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
