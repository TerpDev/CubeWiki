<x-filament-panels::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="bg-primary-100 dark:bg-primary-900 rounded-lg p-2">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Test API Token</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Valideer je token en bekijk tenant data</p>
                </div>
            </div>
        </div>

        <!-- Token Input -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Token Invoer</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        API Token
                    </label>
                    <input
                        type="text"
                        wire:model="token"
                        placeholder="Plak hier je API token..."
                        class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                    />
                </div>

                <button
                    wire:click="testToken"
                    type="button"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 px-4 py-2.5 text-sm font-medium text-white transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Test Token
                </button>
            </div>
        </div>

        @if($tenantData)
            <!-- Tenant Info -->
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg p-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-emerald-500 p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-emerald-900 dark:text-emerald-100 mb-1">Token Gevalideerd!</h3>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 mb-3">De token is gekoppeld aan deze tenant:</p>
                        <div class="grid grid-cols-3 gap-4 bg-white dark:bg-gray-800 rounded-lg p-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tenant ID</dt>
                                <dd class="text-base font-semibold text-gray-900 dark:text-white mt-1">{{ $tenantData['tenant']['id'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Naam</dt>
                                <dd class="text-base font-semibold text-gray-900 dark:text-white mt-1">{{ $tenantData['tenant']['name'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</dt>
                                <dd class="text-base font-semibold text-gray-900 dark:text-white mt-1">{{ $tenantData['tenant']['slug'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-500 rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenantData['statistics']['total_applications'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Applications</div>
                        </div>
                    </div>
                </div>

                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-500 rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenantData['statistics']['total_categories'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Categories</div>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-500 rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenantData['statistics']['total_pages'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Pages</div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Data Lists -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    @if(count($tenantData['applications']) > 0)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Applications ({{ count($tenantData['applications']) }})
                                </h3>
                            </div>
                            <div class="p-4 max-h-64 overflow-y-auto">
                                <ul class="space-y-2">
                                    @foreach($tenantData['applications'] as $app)
                                        <li class="flex items-start gap-2 text-sm">
                                            <span class="text-blue-500 mt-1">•</span>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $app['name'] ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $app['id'] }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(count($tenantData['categories']) > 0)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Categories ({{ count($tenantData['categories']) }})
                                </h3>
                            </div>
                            <div class="p-4 max-h-64 overflow-y-auto">
                                <ul class="space-y-2">
                                    @foreach($tenantData['categories'] as $cat)
                                        <li class="flex items-start gap-2 text-sm">
                                            <span class="text-emerald-500 mt-1">•</span>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $cat['name'] ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $cat['id'] }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(count($tenantData['pages']) > 0)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-purple-50 dark:bg-purple-900/20 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Pages ({{ count($tenantData['pages']) }})
                                </h3>
                            </div>
                            <div class="p-4 max-h-64 overflow-y-auto">
                                <ul class="space-y-2">
                                    @foreach($tenantData['pages'] as $page)
                                        <li class="flex items-start gap-2 text-sm">
                                            <span class="text-purple-500 mt-1">•</span>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $page['title'] ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $page['id'] }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

            <!-- Raw JSON -->
            <div x-data="{ open: false }" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
                >
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Raw JSON Data</span>
                    <svg
                        class="w-5 h-5 text-gray-500 transition-transform"
                        :class="{ 'rotate-180': open }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div
                    x-show="open"
                    x-collapse
                    class="border-t border-gray-200 dark:border-gray-700"
                >
                    <div class="p-4">
                        <pre class="text-xs overflow-auto p-4 bg-gray-900 text-green-400 rounded border border-gray-700 max-h-96">{{ json_encode($tenantData, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

