<x-filament-panels::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div
        class="w-full h-56 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex h-full flex-col items-center justify-center">
            <div>
                <h1 class="font-extrabold text-3xl">Welkom {{$name}}</h1>

            </div>
            <div class="flex items-center text-center justify-center mt-4">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid beatae dolorem doloribus, error id
                    illo laboriosam laborum minus mollitia nam omnis placeat quos sit suscipit veritatis! Excepturi ipsa
                    mollitia repudiandae.</p>

            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-500/10">
                    <svg class="h-6 w-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Applications') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $applicationsCount }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-success-500/10">
                    <svg class="h-6 w-6 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Categories') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $categoriesCount }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-warning-500/10">
                    <svg class="h-6 w-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pages') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pagesCount }}</p>
                </div>
            </div>
        </div>
    </div>
<div>
    <!--Link naar filament.pages.wiki-->
{{--    <a href="{{ route('filament.pages.wiki') }}"--}}
{{--       class="mt-6 inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">--}}
{{--        Go to Wiki Page--}}
{{--    </a>--}}
</div>


</x-filament-panels::page>
