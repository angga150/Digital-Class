<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

    </head>
    <body class="bg-slate-50 text-slate-900 antialiased">

    <div class="min-h-screen">

        {{-- Navbar --}}
        <livewire:navbar />

        {{-- Sidebar & Page Content --}}

        <div class="flex">

            {{-- Sidebar --}}
            <livewire:sidebar />

            {{-- Page Content --}}
            <main class="min-w-0 flex-1">
                {{ $slot }}
            </main>

        </div>

    </div>

        @livewireScripts
    </body>
</html>
