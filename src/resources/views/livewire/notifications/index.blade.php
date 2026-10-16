<div class="mx-auto max-w-3xl" wire:poll.60s>
    <section class="paper-grid mb-7 rounded-3xl border border-line bg-[#fff0d7] p-6 sm:p-8"><p class="text-[10px] font-bold uppercase tracking-widest text-orange-dark">Ada kabar untukmu</p><h1 class="mt-3 text-3xl font-bold tracking-tight">Obrolannya berlanjut.</h1><p class="mt-3 text-sm leading-6 text-muted">Komentar, suka, dan pengumuman baru dari kelasmu. {{ $unread }} belum dibaca.</p></section>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3"><div class="flex gap-2">@foreach(['all' => 'Semua', 'unread' => 'Belum dibaca'] as $value => $label)<button type="button" wire:click="$set('filter', '{{ $value }}')" aria-pressed="{{ $filter === $value ? 'true' : 'false' }}" class="quiet-button {{ $filter === $value ? 'bg-orange/10' : 'text-muted' }}">{{ $label }}</button>@endforeach</div>@if($unread > 0)<button type="button" wire:click="readAll" wire:loading.attr="disabled" class="quiet-button text-xs">Tandai semua dibaca</button>@endif</div>
    <div class="space-y-4">
        @forelse($notifications as $notification)
        <article wire:key="notification-{{ $notification->id }}" class="card p-5 {{ ! $notification->read_at ? 'border-orange/30' : '' }}">
            <div class="flex items-start gap-3"><span class="mt-1 text-orange-dark"><x-icon :name="match($notification->kind) { 'comment' => 'comment', 'like' => 'heart', default => 'megaphone' }" :size="22" /></span><div class="min-w-0 flex-1"><h2 class="text-sm font-bold">{{ $notification->label }}</h2><p class="mt-2 break-words text-sm leading-6 text-muted">{{ \Illuminate\Support\Str::limit($notification->post?->content ?? '', 150) }}</p><p class="mt-2 text-xs text-muted">{{ $notification->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j M Y, H:i') }} WIB · {{ $notification->read_at ? 'Sudah dibaca' : 'Belum dibaca' }}</p></div></div>
            <div class="mt-4 flex flex-wrap gap-2"><button type="button" wire:click="open({{ $notification->id }})" wire:loading.attr="disabled" class="quiet-button text-xs">Buka <x-icon name="arrow" :size="14" /></button><button type="button" wire:click="read({{ $notification->id }}, {{ $notification->read_at ? 'false' : 'true' }})" wire:loading.attr="disabled" class="quiet-button text-xs text-muted">{{ $notification->read_at ? 'Tandai belum dibaca' : 'Tandai dibaca' }}</button></div>
        </article>
        @empty
        <div class="card p-10 text-center"><p class="text-4xl" aria-hidden="true">🌿</p><h2 class="mt-4 text-lg font-bold">Belum ada kabar di sini.</h2><p class="mt-2 text-sm leading-6 text-muted">Notifikasi muncul saat ada interaksi baru di ceritamu atau pengumuman kelas. Coba filter Semua untuk melihat kabar yang sudah dibaca.</p></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $notifications->links() }}</div>
    <p class="mt-6 text-xs leading-6 text-muted">Hanya aktivitas sejak fitur ini aktif. Aktivitasmu sendiri dan simpanan cerita tidak memunculkan notifikasi. Kabar yang sudah tidak bisa diakses otomatis disembunyikan.</p>
</div>
