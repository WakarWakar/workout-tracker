<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Workout Tracker')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 text-gray-900 antialiased">

    @auth
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 sm:px-6">
                <a href="/" class="flex items-center gap-2 text-lg font-semibold tracking-tight text-gray-900">
                    <span class="text-indigo-600">💪</span> Workout Tracker
                </a>
                <div class="flex items-center gap-4">
                    <span class="hidden text-sm text-gray-600 sm:inline">
                        {{ auth()->user()->name }}
                        <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">{{ auth()->user()->role }}</span>
                    </span>
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="btn btn-secondary btn-sm">Logout</button>
                    </form>
                </div>
            </div>
        </header>
    @endauth

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
        @include('partials.alerts')
        @yield('content')
    </main>

</body>
</html>
