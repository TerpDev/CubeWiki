<x-filament-panels::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div
        class="w-full h-56 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex h-full flex-col items-center justify-center">
            <div>
                <h1 class="font-extrabold text-3xl">Welkom {{$name}}</h1>

            </div>
            <div class="flex items-center text-center justify-center mt-4 mx-auto max-w-3xl">
                <p>As you can see here you can create applications, categories and pages. You are connected to your tenant!</p>

            </div>
        </div>
    </div>


</x-filament-panels::page>
