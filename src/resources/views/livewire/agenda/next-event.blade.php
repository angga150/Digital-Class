<section id="agenda" class="card scroll-mt-24 p-5" aria-labelledby="agenda-heading" wire:poll.60s>
    <div class="mb-4 flex items-center justify-between gap-2"><h2 id="agenda-heading" class="flex items-center gap-2 text-sm font-semibold"><x-icon name="calendar" :size="17" />Agenda terdekat</h2><a href="{{ route('events') }}" class="quiet-button min-h-8 px-2 py-1 text-xs">Semua</a></div>
    @if($event)
        <a href="{{ route('events.show', $event) }}" class="flex w-full items-center gap-3 rounded-xl text-left">
            <span class="flex size-14 shrink-0 flex-col items-center justify-center rounded-xl bg-[#fcf4ec] text-orange-dark"><span class="text-xl font-semibold">{{ $event->event_date->timezone('Asia/Jakarta')->format('d') }}</span><span class="text-[10px] font-semibold uppercase">{{ $event->event_date->timezone('Asia/Jakarta')->locale('id')->translatedFormat('M') }}</span></span>
            <span class="min-w-0 flex-1"><span class="block break-words text-sm font-semibold">{{ $event->title }}</span><span class="mt-1 block text-xs text-muted">{{ $event->event_date->timezone('Asia/Jakarta')->locale('id')->translatedFormat('l, H:i') }} WIB</span></span><x-icon name="chevron" :size="15" class="shrink-0 text-muted" />
        </a>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-3"><span class="flex min-w-0 items-start gap-1.5 text-[11px] text-muted"><x-icon name="pin" :size="13" class="shrink-0" /><span class="break-words">{{ $event->location ?: 'Lokasi menyusul' }}</span></span><span class="rounded-md bg-sage px-2 py-1 text-[10px] font-semibold text-[#50683e]">{{ $event->countdown }}</span></div>
    @else
        <p class="text-sm leading-6 text-muted">Belum ada agenda mendatang. Ruang untuk rencana baru masih terbuka 🌿</p><a href="{{ route('events') }}" class="quiet-button mt-3 px-0 text-xs">Lihat agenda kelas <x-icon name="arrow" :size="15" /></a>
    @endif
</section>
