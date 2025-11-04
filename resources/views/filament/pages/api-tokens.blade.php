<x-filament-panels::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-medium">Jou API token</h2>
        </div>

        <div class="space-y-4">
            <!-- Create New Token Button -->
            <div>
                <x-filament::button wire:click="createNewToken">
                    Create New Token
                </x-filament::button>
            </div>

            <!-- Display Token (only shown right after creation) -->
            <div x-data="{ token: '', show: false }"
                 @token-created.window="token = $event.detail.token; show = true">
                <div x-show="show"
                     class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                Token Created Successfully
                            </h3>
                            <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                <p class="mb-2">Please copy your new API token. For security reasons, it won't be shown again.</p>
                                <div class="flex items-center gap-2">
                                    <code x-text="token"
                                          class="block p-2 bg-white dark:bg-gray-800 border border-green-300 dark:border-green-700 rounded text-xs font-mono flex-1 overflow-x-auto">
                                    </code>
                                    <x-filament::button
                                        size="sm"
                                        @click="navigator.clipboard.writeText(token); $tooltip('Copied!', { timeout: 2000 })">
                                        Copy
                                    </x-filament::button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>

