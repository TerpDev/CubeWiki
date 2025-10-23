<div class="">
    <div class="">
        <!-- Page Header -->
        <div class="my-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-4xl">
                {{ \Filament\Facades\Filament::getTenant()->name }} Wiki
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Browse knowledge base and documentation') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- LEFT SIDEBAR: Navigation -->
            <div class="lg:col-span-3">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6">
                        <!-- Navigation Tree -->
                        <div class="space-y-3 max-h-[calc(100vh-16rem)] overflow-y-auto">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ __('Applications') }}
                            </h3>

                            @forelse($applications as $app)
                                <div x-data="{ open: false }"
                                     class="border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                                    <button
                                        @click="open = !open"
                                        class="flex w-full items-center justify-between gap-2 mb-2 rounded-lg p-2 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20">
                                                <svg class="h-4 w-4 text-primary-600 dark:text-primary-400" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-950 dark:text-white">
                                                {{ $app['name'] }}
                                            </h4>
                                        </div>
                                        <svg
                                            class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                            :class="{ 'rotate-180': open }"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    @if(!empty($app['categories']))
                                        <div
                                            x-show="open"
                                            x-collapse
                                            class="space-y-2 ml-2"
                                        >
                                            @foreach($app['categories'] as $category)
                                                <div x-data="{ categoryOpen: false }">
                                                    <button
                                                        @click="categoryOpen = !categoryOpen"
                                                        class="flex w-full items-center justify-between gap-1.5 py-1.5 px-2 rounded-md hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                                                    >
                                                        <div class="flex items-center gap-1.5">
                                                            <svg class="h-3.5 w-3.5 text-gray-400 dark:text-gray-500"
                                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                            </svg>
                                                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                                {{ $category['name'] }}
                                                            </p>
                                                        </div>
                                                        <svg
                                                            class="h-3 w-3 text-gray-400 transition-transform duration-200"
                                                            :class="{ 'rotate-180': categoryOpen }"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </button>

                                                    @if(!empty($category['pages']))
                                                        <ul
                                                            x-show="categoryOpen"
                                                            x-collapse
                                                            class="ml-5 space-y-0.5 mt-1"
                                                        >
                                                            @foreach($category['pages'] as $page)
                                                                <li>
                                                                    <button
                                                                        wire:click="selectPage({{ $app['id'] }}, {{ $category['id'] }}, {{ $page['id'] }})"
                                                                        class="group flex w-full items-center gap-1.5 rounded-md px-2 py-1.5 text-left text-xs transition-colors hover:bg-gray-50 dark:hover:bg-white/5 {{ $selectedPage === $page['id'] ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-400 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}"
                                                                    >

                                                                        <span
                                                                            class="truncate">{{ $page['title'] }}</span>
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT: Page Display -->
            <div class="lg:col-span-9">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6">
                        @if($pageContent)
                            <!-- Breadcrumb -->
                            <nav
                                class="flex items-center gap-2 pb-6 mb-6 text-sm border-b border-gray-200 dark:border-white/10">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span
                                    class="text-gray-600 dark:text-gray-400">{{ $pageContent->category->application->name ?? '' }}</span>
                                <svg class="h-4 w-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                                <span
                                    class="text-gray-600 dark:text-gray-400">{{ $pageContent->category->name ?? '' }}</span>
                            </nav>

                            <!-- Page Title -->
                            <div class="mb-8">
                                <h1 class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-4xl">
                                    {{ $pageContent->title }}
                                </h1>
                            </div>

                            <!-- Page Content -->
                            <div
                                class="prose prose-gray max-w-none dark:prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-h1:text-3xl prose-h2:text-2xl prose-h3:text-xl prose-h4:text-lg prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline dark:prose-a:text-primary-400 prose-code:text-primary-600 dark:prose-code:text-primary-400 prose-code:bg-gray-50 dark:prose-code:bg-white/5 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:font-normal prose-code:before:content-[''] prose-code:after:content-[''] prose-pre:bg-gray-900 dark:prose-pre:bg-black prose-pre:text-gray-100">
                                {!! $pageContent->content !!}
                            </div>

                            <!-- Meta Info -->
                            <div class="mt-12 pt-6 border-t border-gray-200 dark:border-white/10">
                                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ __('Last updated') }}: {{ $pageContent->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @else
                            <!-- Welcome Screen -->
                            <div class="flex flex-col items-center justify-center py-24 text-center">
                                <div
                                    class="flex h-20 w-20 items-center justify-center rounded-full bg-primary-50 dark:bg-primary-900/20">
                                    <svg class="h-10 w-10 text-primary-600 dark:text-primary-400" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold text-gray-950 dark:text-white">
                                    {{ __('Welcome to Cube Wiki') }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Select a page from the sidebar to get started') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
