<div>
    <div class="relative mb-8 overflow-hidden rounded-3xl border border-[#dce4d2] bg-sage p-6 sm:p-9">
        <p class="text-xs font-bold uppercase tracking-[.18em] text-[#526443]">Orang-orang di balik cerita</p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Kenalan dulu, yuk. <span aria-hidden="true">🌿</span></h1>
        <p class="mt-3 max-w-xl text-sm leading-6 text-[#526443]">Satu ruang kelas, banyak warna. Temukan teman diskusi, partner tugas, atau teman jajan berikutnya.</p>
    </div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <h2 class="font-semibold">Teman kelas <span class="ml-2 rounded-full bg-white px-3 py-1 text-sm text-muted">{{ $members->total() }}</span></h2>
        <div class="w-full sm:w-80"><label for="member-search" class="sr-only">Cari nama lengkap teman</label><input id="member-search" type="search" wire:model.live.debounce.300ms="search" maxlength="100" class="field bg-white" placeholder="Cari nama lengkap teman…" /></div>
    </div>
    <div wire:loading.delay role="status" class="mb-3 text-sm text-muted">Mencari teman…</div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($cards as $card)
        <article wire:key="member-{{ $card['id'] }}" class="card flex flex-col p-6">
            <div class="mb-5 flex items-center justify-between gap-3">
                @if($card['avatar'])<img src="{{ $card['avatar'] }}" alt="" class="size-14 rounded-2xl object-cover">@else<span class="flex size-14 items-center justify-center rounded-2xl bg-[#f4dcc8] text-xl font-bold text-[#834d2a]">{{ $card['initials'] }}</span>@endif
                <span class="rounded-full bg-sage px-3 py-1 text-[11px] font-semibold text-[#526443]">{{ $card['role'] }}</span>
            </div>
            <h3 class="break-words text-lg font-bold">{{ $card['name'] }}</h3>
            <p class="mt-2 line-clamp-3 break-words text-sm leading-6 text-muted">{{ $card['visible'] ? ($card['bio'] ?: 'Belum menulis cerita. Kenalan langsung dulu, yuk!') : 'Detail profil dibatasi oleh pemiliknya.' }}</p>
            <div class="mb-5 mt-4 flex flex-wrap gap-2">@foreach(array_slice($card['hobby'], 0, 3) as $hobby)<span class="max-w-full break-words rounded-lg bg-paper px-2 py-1 text-xs text-muted">{{ $hobby }}</span>@endforeach</div>
            <a href="{{ route('members.show', $card['id']) }}" class="quiet-button mt-auto justify-between border-t border-line pt-4">Lihat cerita profil <x-icon name="arrow" :size="16" /></a>
        </article>
        @empty
        <div class="card col-span-full p-10 text-center"><p class="text-3xl" aria-hidden="true">🔎</p><h3 class="mt-3 font-bold">Belum ketemu, nih.</h3><p class="mt-2 text-sm text-muted">Coba cari dengan nama lengkap yang lain.</p></div>
        @endforelse
    </div>
    <div class="mt-8">{{ $members->links() }}</div>
</div>
