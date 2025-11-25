<?php

namespace App\Filament\Admin\Resources\Tenants\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
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

                TextColumn::make('tokens_count')
                    ->label('API Tokens')
                    ->counts('tokens')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->sortable(),

                TextColumn::make('applications_count')
                    ->label('Applications')
                    ->counts('applications')
                    ->badge()
                    ->color('success')
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
                Action::make('create_token')
                    ->label('Create Token')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Create API Token')
                    ->modalDescription(fn ($record) => "Create a new API token for {$record->name}?")
                    ->modalSubmitActionLabel('Create Token')
                    ->action(function ($record): void {
                        // Create new token (allows multiple tokens per tenant)
                        $token = $record->createToken('admin-created-token')->plainTextToken;

                        Notification::make()
                            ->title('Token Created')
                            ->body("Token: {$token}")
                            ->success()
                            ->duration(null)
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

            ]);
    }
}
