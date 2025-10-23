<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        {{ __('Welcome back') }}, {{ auth()->user()->name }}!
                    </h1>
                    <p class="mt-2 text-primary-100">
                        {{ __('Here\'s what\'s happening with your workspace today.') }}
                    </p>
                </div>
                <div class="hidden lg:block">
                    <svg class="h-20 w-20 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Links Section -->
        <div>
            <h2 class="text-xl font-semibold text-gray-950 dark:text-white mb-4">
                {{ __('Quick Actions') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Wiki Link -->
                <a href="{{ route('filament.wiki.pages.wiki-browse', ['tenant' => \Filament\Facades\Filament::getTenant()]) }}"
                   class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 transition hover:shadow-md dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/20 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/30 transition">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-950 dark:text-white">{{ __('Cube Wiki') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Browse knowledge base') }}</p>
                            </div>
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-indigo-400 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                </a>

                <!-- Applications Link -->
                <a href="{{ route('filament.member.resources.applications.index', ['tenant' => \Filament\Facades\Filament::getTenant()]) }}"
                   class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 transition hover:shadow-md dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/30 transition">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-950 dark:text-white">{{ __('Applications') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Manage applications') }}</p>
                            </div>
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-blue-400 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                </a>

                <!-- Categories Link -->
                <a href="{{ route('filament.member.resources.categories.index', ['tenant' => \Filament\Facades\Filament::getTenant()]) }}"
                   class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 transition hover:shadow-md dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20 group-hover:bg-green-200 dark:group-hover:bg-green-900/30 transition">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-950 dark:text-white">{{ __('Categories') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Manage categories') }}</p>
                            </div>
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-green-400 to-green-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div>
            <h2 class="text-xl font-semibold text-gray-950 dark:text-white mb-4">
                {{ __('Overview') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Applications -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Applications') }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                                {{ \Filament\Facades\Filament::getTenant()->applications()->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Categories') }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                                {{ \Filament\Facades\Filament::getTenant()->categories()->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Pages -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Wiki Pages') }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                                {{ \Filament\Facades\Filament::getTenant()->pages()->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/20">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Team Members') }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                                {{ \Filament\Facades\Filament::getTenant()->users()->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/20">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

