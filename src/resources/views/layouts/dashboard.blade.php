<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#faf8f3">
    <meta name="description" content="Ruang cerita, agenda, dan kenangan bersama kelas kita.">
    <title>{{ $title ?? 'Beranda — Digital Class' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="classDashboard">
    <a href="#main-content" class="fixed left-4 top-4 z-50 -translate-y-32 rounded-xl bg-white p-3 focus:translate-y-0">Lewati ke konten</a>
    <x-dashboard.sidebar />
    <div class="lg:pl-[232px]">
        <x-dashboard.navbar />
        <main id="main-content" class="mx-auto max-w-[1360px] px-4 pb-28 pt-6 sm:px-7 lg:px-10 lg:pb-8 lg:pt-8">
            {{ $slot }}
        </main>
    </div>
    <x-dashboard.mobile-nav />
    <x-dashboard.dialogs />
    <div x-cloak x-show="toast" x-transition.opacity role="status" aria-live="polite" class="fixed bottom-24 left-1/2 z-50 w-max max-w-[calc(100%-2rem)] -translate-x-1/2 rounded-2xl bg-ink px-5 py-3 text-center text-sm text-white shadow-xl lg:bottom-6" x-text="toast"></div>
    @livewireScripts
</body>
</html>
