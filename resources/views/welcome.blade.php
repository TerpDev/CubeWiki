<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="flex items-center justify-center h-screen bg-gray-50 text-gray-800">
<div class="text-center space-y-6">
    <div>
        <h1 class="text-4xl font-bold mb-2">Welcome to {{ config('app.name') }}</h1>
        <p class="text-gray-600 text-lg">Wiki Cube</p>
    </div>

    <a href="{{ url('/member/login') }}"
       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
        Go to Member Login
    </a>

    <footer class="text-gray-400 text-sm pt-6">
        &copy; {{ date('Y') }} {{ config('app.name') }} — All rights reserved.
    </footer>
</div>
</body>
</html>
