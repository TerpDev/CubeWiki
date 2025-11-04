<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ApiTokenDebugWidget extends Widget
{
    protected string $view = 'filament.widgets.api-token-debug-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 99;

    public function getTokenData(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return [
            'has_session_token' => session()->has('api_token'),
            'session_token' => session('api_token'),
            'token_count' => $user->tokens()->count(),
            'last_token' => $user->tokens()->latest()->first(),
        ];
    }
}

