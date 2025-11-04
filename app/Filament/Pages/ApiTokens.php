<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ApiTokens extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-key';

    protected string $view = 'filament.pages.api-tokens';

    protected static ?string $navigationLabel = 'API Tokens';

    protected static ?string $title = 'API Tokens';

    public function mount(): void
    {
        // Check if there's a token in session (just created on login)
        if (session()->has('api_token')) {
            $this->dispatch('token-created', token: session()->get('api_token'));
        }
    }

    public function createNewToken()
    {
        $user = auth()->user();

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Store in session
        session()->put('api_token', $token);

        Notification::make()
            ->title('Token Created')
            ->success()
            ->body('Your new API token has been created. Copy it now as it will not be shown again.')
            ->send();

        $this->dispatch('token-created', token: $token);
    }

    public function getTokensProperty()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return $user->tokens;
    }
}

