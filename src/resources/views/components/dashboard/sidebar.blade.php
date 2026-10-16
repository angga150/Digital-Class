<aside class="fixed inset-y-0 left-0 z-30 hidden w-[232px] flex-col border-r border-line bg-white/70 px-5 py-7 lg:flex">
    <div class="px-2"><x-brand /></div>
    <a href="{{ route('members') }}" class="mt-7 flex items-center gap-2 rounded-xl bg-[#f2efe8] px-3 py-2.5 text-xs text-muted"><x-icon name="users" :size="16" /> {{ \App\Support\CurrentClass::membership()?->classModel->name }} <x-icon name="chevron" :size="13" class="ml-auto" /></a>
    <p class="mb-3 mt-8 px-4 text-[10px] font-bold uppercase tracking-[.18em] text-muted">Ruang bersama</p>
    <nav aria-label="Navigasi utama" class="space-y-1.5">
        @foreach (['beranda' => ['home', 'Beranda'], 'cerita' => ['feed', 'Cerita Kelas'], 'agenda' => ['calendar', 'Agenda'], 'kenangan' => ['image', 'Kenangan']] as $id => [$icon, $label])
        <a href="#{{ $id }}" @click="active = '{{ $id }}'" class="nav-item" :class="{ 'is-active': active === '{{ $id }}' }" :aria-current="active === '{{ $id }}' ? 'location' : null"><x-icon :name="$icon" />{{ $label }}</a>
        @endforeach
        <a href="{{ route('members') }}" class="nav-item w-full"><x-icon name="users" />Teman Kelas</a>
        <a href="{{ route('feed', ['filter' => 'saved']) }}" class="nav-item w-full"><x-icon name="feed" />Cerita Tersimpan</a>
    </nav>
    <div class="relative mt-auto">
        <div class="mb-6 rounded-2xl bg-sage/60 p-4">
            <x-icon name="sparkle" class="mb-2 text-[#62784a]" :size="23" />
            <p class="text-sm font-semibold">Cerita kita, rumah kita.</p>
            <p class="mt-1.5 text-xs leading-5 text-muted">Hal kecil hari ini bisa jadi kenangan favorit nanti.</p>
        </div>
        <button @click="openModal('settings')" class="nav-item w-full"><x-icon name="settings" />Tentang demo</button>
        <div class="mt-4 border-t border-line pt-5">
            <a href="{{ route('profile') }}" class="flex w-full items-center gap-3 rounded-xl p-1 text-left">
                <x-avatar :initials="auth()->user()->initials" /><span class="min-w-0 flex-1"><span class="block truncate text-sm font-semibold">{{ auth()->user()->display_name }}</span><span class="text-xs text-muted">Profil & privasi</span></span><x-icon name="chevron" :size="16" />
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">@csrf<button class="quiet-button w-full text-muted" type="submit">Keluar</button></form>
        </div>
    </div>
</aside>
