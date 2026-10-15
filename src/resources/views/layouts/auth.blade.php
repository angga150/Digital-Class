<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#faf8f3"><title>{{ $title ?? 'Selamat datang — Digital Class' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <main class="mx-auto grid min-h-screen max-w-[1440px] lg:grid-cols-2">
        <section class="relative hidden flex-col justify-between overflow-hidden border-r border-line bg-[#eeeedd] p-12 lg:flex xl:p-16">
            <x-brand />
            <div class="relative my-12">
                <span class="inline-flex -rotate-3 rounded-lg bg-[#ffe8a9] px-4 py-2 font-story text-sm">Satu kelas. Banyak cerita.</span>
                <h1 class="mt-8 text-5xl font-bold leading-[1.15] tracking-tight">Tempat kecil untuk<br>kenangan <span class="text-orange-dark">besar.</span></h1>
                <p class="mt-5 max-w-sm text-base leading-7 text-[#626953]">Dari tugas bareng sampai obrolan random. Semua punya tempat di ruang kelas kita.</p>
                <figure class="relative mt-12 rotate-[-3deg] border border-white bg-white p-3 pb-6 shadow-lg">
                    <span class="tape"></span>
                    <img src="{{ asset('img/dashboard/campus-friends.png') }}" width="1774" height="887" alt="Ilustrasi teman-teman berbincang di taman kampus" class="aspect-[2/1] w-full object-cover">
                    <figcaption class="mt-4 text-center font-story text-sm text-[#626953]">Yang bikin kangen, orang-orangnya ♡</figcaption>
                </figure>
            </div>
            <p class="text-xs text-[#626953]">Ruang privat kelas · Bertumbuh dan bercerita bersama</p>
        </section>
        <section class="flex flex-col justify-center px-6 py-10 sm:px-12 lg:px-16">
            <div class="mb-10 lg:hidden"><x-brand /></div>
            <div class="mx-auto w-full max-w-md">
                <x-form.feedback />
                {{ $slot }}
            </div>
            <p class="mx-auto mt-10 max-w-sm text-center text-xs leading-5 text-muted">Dibuat untuk kelas kita, dan cerita yang tumbuh di dalamnya.</p>
        </section>
    </main>
    @livewireScripts
</body>
</html>
