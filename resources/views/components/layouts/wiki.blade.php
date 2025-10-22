<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name','Cube Wiki') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
<header class="bg-white border-b">
    @auth
        <a href="{{ route('manage.tenants') }}" class="text-sm text-gray-700 hover:underline">My Wiki Admin</a>
    @endauth

    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center gap-4">
        <a href="{{ route('home') }}" class="font-bold text-xl">Cube Wiki</a>
        @isset($tenant)
            <span class="text-gray-400">/</span>
            <a href="{{ route('tenant.home', $tenant->slug) }}" class="text-gray-700 hover:text-black font-medium">
                {{ $tenant->name }}
            </a>
        @endisset
        <nav class="ml-auto flex gap-4">
            @isset($tenant)
                <a href="{{ route('tenant.categories',$tenant->slug) }}" class="text-sm text-gray-700 hover:text-black">Categories</a>
                <a href="{{ route('tenant.Pages',$tenant->slug) }}" class="text-sm text-gray-700 hover:text-black">Pages</a>
                <a href="{{ route('tenant.search',$tenant->slug) }}" class="text-sm text-gray-700 hover:text-black">Search</a>
            @endisset
        </nav>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="max-w-6xl mx-auto px-4 pb-10 text-sm text-gray-500">
    © {{ date('Y') }} Cube Wiki
</footer>
</body>
</html>
