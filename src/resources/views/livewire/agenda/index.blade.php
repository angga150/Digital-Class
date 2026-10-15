<div wire:poll.60s>
    <section class="paper-grid mb-7 rounded-3xl border border-[#f0e0c5] bg-[#fff0d7] p-6 sm:p-9">
        <p class="text-xs font-bold uppercase tracking-[.18em] text-orange-dark">Biar nggak cuma jadi wacana</p>
        <div class="mt-3 flex flex-wrap items-end justify-between gap-5">
            <div><h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Ada rencana apa? <span aria-hidden="true">☀️</span></h1><p class="mt-3 max-w-xl text-sm leading-6 text-[#7d6c53]">Dari deadline sampai kumpul bareng. Simpan tanggalnya, siapkan ceritanya.</p></div>
            @can('create', \App\Models\Event::class)<button wire:click="create" type="button" class="primary-button"><x-icon name="calendar" :size="18" />Buat agenda</button>@endcan
        </div>
    </section>

    @if($notice)<p role="status" class="mb-5 rounded-2xl bg-sage p-4 text-sm leading-6 text-[#435834]">{{ $notice }}</p>@endif
    @if($errors->any())<div role="alert" class="mb-5 rounded-2xl bg-red-50 p-4 text-sm text-red-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

    @if($formOpen)
    <form wire:submit="save" class="card mb-7 p-5 sm:p-7" aria-labelledby="agenda-form-heading">
        <div class="mb-5 flex items-center justify-between gap-4"><div><h2 id="agenda-form-heading" class="text-xl font-bold">{{ $editing ? 'Rapikan rencana kelas' : 'Mulai satu rencana baru' }}</h2><p class="mt-2 text-xs text-muted">Semua jadwal diisi dan ditampilkan dalam WIB (Asia/Jakarta).</p></div><button type="button" wire:click="closeForm" class="icon-button" aria-label="Tutup form agenda"><x-icon name="close" /></button></div>
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2"><x-form.input name="title" label="Nama kegiatan" wire:model="title" required maxlength="150" placeholder="Misalnya: piknik akhir semester" /></div>
            <div class="sm:col-span-2"><label for="event-description" class="mb-2 block text-sm font-semibold">Tentang kegiatannya · opsional</label><textarea id="event-description" wire:model="description" rows="3" maxlength="3000" class="field" placeholder="Apa yang perlu disiapkan teman-teman?"></textarea></div>
            <x-form.input name="starts" label="Mulai · WIB" type="datetime-local" wire:model="starts" max="2038-01-19T10:14" required />
            <x-form.input name="ends" label="Selesai · WIB" type="datetime-local" wire:model="ends" max="2038-01-19T10:14" required />
            <div><label for="event-type" class="mb-2 block text-sm font-semibold">Jenis kegiatan</label><select id="event-type" wire:model="type" class="field"><option value="academic">Akademik</option><option value="social">Kumpul bareng</option><option value="deadline">Deadline</option><option value="other">Lainnya</option></select></div>
            <x-form.input name="location" label="Lokasi · opsional" wire:model="location" maxlength="255" placeholder="Kosongkan jika lokasi menyusul" />
            <div class="sm:col-span-2"><x-form.input name="link" label="Tautan kegiatan · opsional" type="url" wire:model="link" maxlength="255" placeholder="https://…" /><p class="mt-2 text-xs text-muted">Bisa tautan ruang pertemuan atau informasi kegiatan.</p></div>
        </div>
        <div class="mt-6 flex flex-wrap items-center justify-between gap-3"><p class="text-xs text-muted">Agenda terlihat oleh teman satu kelas.</p><div class="flex gap-2"><button wire:click="closeForm" type="button" class="quiet-button text-muted">Batal</button><button type="submit" wire:loading.attr="disabled" wire:target="save" class="primary-button"><span wire:loading.remove wire:target="save">Simpan agenda</span><span wire:loading wire:target="save">Menyimpan…</span></button></div></div>
    </form>
    @endif

    <div class="mb-6 flex flex-wrap items-center gap-2" aria-label="Filter status agenda">
        @foreach(['upcoming' => 'Akan datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'all' => 'Semua'] as $value => $label)
        <button type="button" wire:click="$set('filter', '{{ $value }}')" aria-pressed="{{ $filter === $value ? 'true' : 'false' }}" class="quiet-button {{ $filter === $value ? 'bg-orange/10' : 'text-muted' }}">{{ $label }}</button>
        @endforeach
    </div>

    <div class="grid items-stretch gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse($events as $event)
        <article wire:key="agenda-{{ $event->id }}" class="card flex flex-col p-5 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div class="flex size-16 shrink-0 flex-col items-center justify-center rounded-2xl bg-[#fff0e5] text-orange-dark"><span class="text-2xl font-bold">{{ $event->event_date->timezone('Asia/Jakarta')->format('d') }}</span><span class="text-[11px] font-semibold uppercase">{{ $event->event_date->timezone('Asia/Jakarta')->locale('id')->translatedFormat('M Y') }}</span></div>
                <span class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $event->status === 'cancelled' ? 'bg-red-50 text-red-800' : 'bg-sage text-[#526443]' }}">{{ $event->status_label }}</span>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-muted">{{ ['academic' => 'Akademik', 'social' => 'Kumpul bareng', 'deadline' => 'Deadline', 'other' => 'Lainnya'][$event->type] }}</p>
            <h2 class="mt-2 break-words text-xl font-bold leading-7"><a href="{{ route('events.show', $event) }}" class="hover:text-orange-dark">{{ $event->title }}</a></h2>
            <p class="mt-3 flex items-center gap-2 text-xs text-muted"><x-icon name="clock" :size="15" />{{ $event->event_date->timezone('Asia/Jakarta')->format('H:i') }} WIB · {{ $event->countdown }}</p>
            <p class="mt-3 flex items-start gap-2 break-words text-sm text-muted"><x-icon name="pin" :size="16" class="mt-0.5 shrink-0" /><span class="min-w-0 break-words">{{ $event->location ?: 'Lokasi menyusul' }}</span></p>
            <p class="mb-5 mt-4 line-clamp-3 break-words text-sm leading-6 text-muted">{{ $event->description ?: 'Detail kegiatan akan dibagikan oleh pengurus kelas.' }}</p>
            <div class="mt-auto flex items-center justify-between gap-2 border-t border-line pt-3"><span class="text-xs text-muted">{{ $event->going_count }} konfirmasi hadir</span><a href="{{ route('events.show', $event) }}" class="quiet-button text-xs">Lihat detail <x-icon name="arrow" :size="15" /></a></div>
            @can('delete', $event)
            <div class="mt-3 flex flex-wrap items-center gap-1 border-t border-line pt-3">
                @can('update', $event)<button type="button" wire:click="edit({{ $event->id }})" class="quiet-button text-xs" aria-label="Edit agenda {{ $event->title }}">Edit</button>@endcan
                @can('cancel', $event)<button type="button" wire:click="cancel({{ $event->id }})" wire:confirm="Batalkan kegiatan ini? Kegiatan akan tetap tercatat dengan label Dibatalkan." wire:loading.attr="disabled" class="quiet-button text-xs" aria-label="Batalkan agenda {{ $event->title }}">Batalkan acara</button>@endcan
                <button type="button" wire:click="delete({{ $event->id }})" wire:confirm="Hapus agenda dari daftar kelas? Catatan disimpan untuk pemulihan administratif." wire:loading.attr="disabled" class="quiet-button text-xs text-muted" aria-label="Hapus agenda {{ $event->title }}">Hapus</button>
            </div>
            @endcan
        </article>
        @empty
        <div class="card col-span-full p-10 text-center"><p class="text-4xl" aria-hidden="true">🌿</p><h2 class="mt-4 text-lg font-bold">Belum ada agenda di sini.</h2><p class="mx-auto mt-2 max-w-md text-sm leading-6 text-muted">Coba lihat status lain. Kalau ada rencana baru, pengurus kelas bisa menambahkannya di sini.</p></div>
        @endforelse
    </div>
    <div class="mt-7">{{ $events->links() }}</div>
</div>
