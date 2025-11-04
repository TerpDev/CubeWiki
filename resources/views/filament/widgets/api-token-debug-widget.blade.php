<x-filament-widgets::widget>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-filament::section>
        <x-slot name="heading">
            🔍 API Token Debug Info
        </x-slot>

        @php
            $data = $this->getTokenData();
        @endphp

        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded">
                <span class="font-medium">Session has token:</span>
                <span class="flex items-center gap-2">
                    @if($data['has_session_token'])
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-600 dark:text-green-400 font-semibold">Yes</span>
                    @else
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-600 dark:text-red-400 font-semibold">No</span>
                    @endif
                </span>
            </div>

            @if($data['has_session_token'])
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded">
                    <div class="font-medium mb-2">Session Token (first 20 chars):</div>
                    <code class="text-xs bg-white dark:bg-gray-900 px-2 py-1 rounded border border-gray-200 dark:border-gray-700">
                        {{ Str::limit($data['session_token'], 20, '...') }}
                    </code>
                </div>
            @endif

            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded">
                <span class="font-medium">Total tokens in DB:</span>
                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                    {{ $data['token_count'] }}
                </span>
            </div>

            @if($data['last_token'])
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded">
                    <div class="font-medium mb-2">Last Token Info:</div>
                    <div class="space-y-1 text-xs">
                        <div><span class="text-gray-500">Name:</span> <span class="font-mono">{{ $data['last_token']->name }}</span></div>
                        <div><span class="text-gray-500">Created:</span> {{ $data['last_token']->created_at->diffForHumans() }}</div>
                        <div><span class="text-gray-500">Last Used:</span> {{ $data['last_token']->last_used_at ? $data['last_token']->last_used_at->diffForHumans() : 'Never' }}</div>
                    </div>
                </div>
            @endif

            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                <div class="text-xs text-blue-700 dark:text-blue-300">
                    <strong>Note:</strong> If "Session has token" shows "No" after login, the Login event might not be firing.
                    Go to <a href="{{ route('filament.member.pages.api-tokens', ['tenant' => filament()->getTenant()]) }}" class="underline font-semibold">API Tokens</a> to manually create one.
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

