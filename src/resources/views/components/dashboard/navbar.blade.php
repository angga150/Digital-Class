<header class="sticky top-0 z-20 border-b border-line bg-paper/95 backdrop-blur-lg">
    <div class="mx-auto flex h-20 max-w-[1360px] items-center justify-between gap-4 px-4 sm:px-7 lg:px-10">
        <div class="lg:hidden"><x-brand /></div>
        <div class="relative hidden w-full max-w-[420px] md:block" @click.outside="searchOpen = false" @keydown.escape="searchOpen = false">
            <label for="dashboard-search" class="sr-only">Cari cerita, agenda, atau teman</label>
            <x-icon name="search" class="pointer-events-none absolute left-4 top-3.5 text-muted" :size="18" />
            <input id="dashboard-search" type="search" x-model="search" @input="searchOpen = true" @focus="searchOpen = true" :aria-expanded="searchOpen && search.trim().length > 0" aria-controls="search-results" autocomplete="off" placeholder="Cari cerita, agenda, atau teman..." class="field border-transparent bg-[#f0eee8] pl-11">
            <div id="search-results" x-cloak x-show="searchOpen && search.trim()" class="absolute inset-x-0 top-14 rounded-2xl border border-line bg-white p-2 shadow-lg">
                <template x-for="result in searchResults" :key="result.label"><button @click="goToResult(result)" class="block min-h-11 w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-paper" x-text="result.label"></button></template>
                <p x-show="!searchResults.length" class="p-3 text-sm text-muted">Belum ketemu. Coba “agenda” atau “Rani”.</p>
            </div>
        </div>
        <div class="ml-auto flex items-center gap-2 sm:gap-4">
            <button @click="openModal('settings')" class="hidden rounded-full border border-[#eadbc7] bg-[#fff7e9] px-3 py-1 text-[11px] font-semibold text-[#83613a] sm:block">Konten contoh</button>
            <button @click="openModal('notifications')" class="icon-button relative" aria-label="Lihat pemberitahuan"><x-icon name="bell" :size="22" /><span x-show="!notificationsRead" class="absolute right-3 top-2.5 size-2 rounded-full bg-orange ring-2 ring-paper"></span></button>
            <span class="hidden h-7 w-px bg-line sm:block"></span>
            <a href="{{ route('members') }}" class="icon-button lg:hidden" aria-label="Teman kelas"><x-icon name="users" :size="21" /></a>
            <a href="{{ route('profile') }}" class="rounded-full" aria-label="Buka profilku"><x-avatar :initials="auth()->user()->initials" /></a>
        </div>
    </div>
</header>
