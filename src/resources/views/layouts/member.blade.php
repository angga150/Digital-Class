<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#faf8f3"><title>{{ $title ?? 'Ruang teman — Digital Class' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <header class="border-b border-line bg-white/80">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-5 py-5 sm:px-8">
            <x-brand />
            <nav aria-label="Navigasi akun" class="flex flex-wrap items-center gap-1 sm:gap-3">
                <a href="{{ route('dashboard') }}" class="quiet-button text-muted">Beranda</a>
                <a href="{{ route('feed') }}" class="quiet-button {{ request()->routeIs('feed') ? 'bg-orange/10' : 'text-muted' }}">Cerita</a>
                <a href="{{ route('events') }}" class="quiet-button {{ request()->routeIs('events*') ? 'bg-orange/10' : 'text-muted' }}">Agenda</a>
                <a href="{{ route('members') }}" class="quiet-button {{ request()->routeIs('members*') ? 'bg-orange/10' : 'text-muted' }}">Teman</a>
                <a href="{{ route('profile') }}" class="quiet-button {{ request()->routeIs('profile') ? 'bg-orange/10' : 'text-muted' }}">Profilku</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="quiet-button text-muted" type="submit">Keluar</button></form>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-5 py-8 sm:px-8 sm:py-12">
        <x-form.feedback />
        {{ $slot }}
    </main>
    <footer class="mx-auto max-w-6xl border-t border-line px-5 py-6 text-xs text-muted sm:px-8">Digital Class · Ruang kecil, banyak cerita.</footer>
    @livewireScripts
</body>
</html>
