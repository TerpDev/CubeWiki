<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_token')
                ->label('Create New API Token')
                ->icon('heroicon-o-key')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Create New API Token')
                ->modalDescription('This will revoke all existing tokens and create a new one. Are you sure?')
                ->modalSubmitActionLabel('Create Token')
                ->action(function () {
                    $user = $this->record;

                    // Delete old tokens
                    $user->tokens()->delete();

                    // Create new token
                    $token = $user->createToken('admin-created-token')->plainTextToken;

                    Notification::make()
                        ->title('New Token Created')
                        ->body("API Token (copy now): {$token}")
                        ->success()
                        ->duration(null) // Don't auto-hide
                        ->send();
                }),


            DeleteAction::make(),
        ];
    }
}
