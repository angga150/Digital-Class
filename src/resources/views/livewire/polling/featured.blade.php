<section wire:poll.60s aria-label="Polling kelas aktif">
    <div class="mb-3 flex items-center justify-between gap-2"><h2 class="flex items-center gap-2 text-sm font-semibold"><x-icon name="chart" :size="17" />Yuk, pilih bareng</h2><a href="{{ route('polls') }}" class="quiet-button text-xs">Semua polling</a></div>
    @if($poll)<livewire:polling.ballot :poll-id="$poll->id" :key="'featured-poll-'.$poll->id" />
    @else<div class="card p-5"><p class="text-sm leading-6 text-muted">Belum ada polling aktif. Satu ide kecil bisa jadi rencana bersama 💬</p><a href="{{ route('polls') }}" class="quiet-button mt-3 px-0 text-xs">Lihat suara kelas →</a></div>@endif
</section>
