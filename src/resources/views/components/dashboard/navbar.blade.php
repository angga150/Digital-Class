<header class="sticky top-0 z-20 border-b border-line bg-paper/95 backdrop-blur-lg">
    <div class="mx-auto flex h-20 max-w-[1360px] items-center justify-between gap-4 px-4 sm:px-7 lg:px-10">
        <div class="lg:hidden"><x-brand /></div>
        <form action="{{ route('search') }}" method="GET" class="relative hidden w-full max-w-[420px] md:block">
            <label for="dashboard-search" class="sr-only">Cari di kelas</label>
            <x-icon name="search" class="pointer-events-none absolute left-4 top-3.5 text-muted" :size="18" />
            <input id="dashboard-search" name="q" type="search" minlength="2" maxlength="100" required placeholder="Cari di kelas, lalu tekan Enter…" class="field border-transparent bg-[#f0eee8] pl-11">
        </form>
        <div class="ml-auto flex items-center gap-2 sm:gap-4"><a href="{{ route('search') }}" class="icon-button md:hidden" aria-label="Cari di kelas"><x-icon name="search" :size="21" /></a>
            <button @click="openModal('settings')" class="hidden rounded-full border border-[#eadbc7] bg-[#fff7e9] px-3 py-1 text-[11px] font-semibold text-[#83613a] sm:block">Tentang kelas</button>
            <livewire:notifications.bell />
            <span class="hidden h-7 w-px bg-line sm:block"></span>
            <a href="{{ route('members') }}" class="icon-button lg:hidden" aria-label="Teman kelas"><x-icon name="users" :size="21" /></a>
            <a href="{{ route('profile') }}" class="rounded-full" aria-label="Buka profilku"><x-avatar :initials="auth()->user()->initials" /></a>
        </div>
    </div>
</header>
