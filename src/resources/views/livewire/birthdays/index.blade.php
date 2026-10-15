<div wire:poll.60s>
    <section class="paper-grid mb-7 overflow-hidden rounded-3xl border border-[#f4e6aa] bg-[#fff3c8] p-6 sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-5">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-[#885c19]">Sedikit perhatian, banyak senyuman</p><h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Satu hari untuk dirayakan.</h1><p class="mt-3 max-w-xl text-sm leading-6 text-muted">Catat hari spesial teman sekelas. Kalender hanya menampilkan ulang tahun yang boleh kamu lihat.</p></div>
            <span class="text-6xl" aria-hidden="true">🎂</span>
        </div>
        <div class="mt-6 flex flex-wrap gap-3"><span class="rounded-full bg-white/80 px-4 py-2 text-xs font-semibold">{{ $todayCount }} merayakan hari ini</span><span class="rounded-full bg-white/80 px-4 py-2 text-xs font-semibold">{{ $soonCount }} dalam 30 hari ke depan</span></div>
    </section>
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div><label for="birthday-month" class="mb-2 block text-sm font-semibold">Cari bulan spesialnya</label><select id="birthday-month" wire:model.live="month" class="field min-w-48"><option value="all">Semua bulan</option>@foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $label)<option value="{{ $index + 1 }}">{{ $label }}</option>@endforeach</select></div>
        <a href="{{ route('profile') }}" class="quiet-button text-sm">Atur ulang tahun & privasiku <x-icon name="arrow" :size="16" /></a>
    </div>
    <p class="mb-5 text-xs leading-6 text-muted">Diurutkan dari perayaan berikutnya. Tahun lahir dan usia tidak ditampilkan. Ulang tahun 29 Februari dirayakan pada 28 Februari di tahun biasa.</p>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($birthdays as $birthday)
        <article wire:key="birthday-{{ $birthday['id'] }}" class="card min-w-0 p-5 {{ $birthday['days'] === 0 ? 'border-[#f4d77a] bg-[#fffdf5]' : '' }}">
            <div class="mb-5 flex items-center justify-between gap-3"><span class="flex size-16 shrink-0 flex-col items-center justify-center rounded-2xl bg-[#fff3c8] text-[#885c19]"><span class="text-2xl font-bold">{{ $birthday['day'] }}</span><span class="text-xs font-semibold uppercase">{{ $birthday['month_label'] }}</span></span><span class="rounded-full bg-sage px-3 py-1.5 text-xs font-semibold text-[#526443]">{{ $birthday['countdown'] }}</span></div>
            <a href="{{ route('members.show', $birthday['id']) }}" class="flex items-center gap-3">
                @if($birthday['avatar'])<img src="{{ $birthday['avatar'] }}" alt="" loading="lazy" class="size-11 shrink-0 rounded-2xl object-cover">@else<x-avatar :initials="$birthday['initials']" tone="sage" class="size-11 shrink-0" />@endif
                <span class="min-w-0"><span class="block break-words text-lg font-bold">{{ $birthday['name'] }}</span><span class="mt-1 block text-xs text-muted">{{ $birthday['own'] ? 'Ini hari spesialmu ♡' : 'Teman sekelas' }}</span></span>
            </a>
            <p class="mt-5 border-t border-line pt-4 text-xs leading-6 text-muted">Perayaan berikutnya · {{ $birthday['label'] }}</p>
            @if($birthday['leap_adjusted'])<p class="mt-2 text-xs leading-5 text-muted">Lahir 29 Februari, dirayakan 28 Februari pada tahun perayaan ini.</p>@endif
            @if($birthday['days'] === 0)<p class="mt-3 font-story text-sm text-orange-dark">Selamat ulang tahun! Semoga banyak hal baik datang.</p>@endif
        </article>
        @empty
        <div class="card col-span-full p-10 text-center"><p class="text-4xl" aria-hidden="true">🌷</p><h2 class="mt-4 text-lg font-bold">Belum ada ulang tahun yang bisa ditampilkan.</h2><p class="mx-auto mt-2 max-w-md text-sm leading-6 text-muted">Coba bulan lain. Kamu juga bisa mengisi tanggal lahir dan mengatur siapa yang boleh melihatnya di Profilku.</p></div>
        @endforelse
    </div>
</div>
