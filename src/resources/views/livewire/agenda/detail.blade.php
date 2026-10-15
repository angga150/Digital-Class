<div class="mx-auto max-w-3xl" wire:poll.60s>
    <a href="{{ route('events') }}" class="quiet-button mb-5">← Semua agenda kelas</a>
    @if($notice)<p role="status" class="mb-5 rounded-2xl bg-sage p-4 text-sm leading-6 text-[#435834]">{{ $notice }}</p>@endif
    @if($errors->any())<p role="alert" class="mb-5 rounded-2xl bg-red-50 p-4 text-sm text-red-800">Pilihan belum tersimpan. Coba pilih kembali.</p>@endif
    <article class="card overflow-hidden">
        <header class="paper-grid border-b border-line bg-[#fff0d7] p-6 sm:p-9">
            <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $event->status === 'cancelled' ? 'bg-red-50 text-red-800' : 'bg-white text-orange-dark' }}">{{ $event->status_label }}</span>
            <h1 class="mt-5 break-words text-3xl font-bold leading-tight tracking-tight sm:text-4xl">{{ $event->title }}</h1>
            <p class="mt-4 flex items-center gap-2 text-sm font-semibold text-orange-dark"><x-icon name="clock" :size="17" />{{ $event->countdown }}</p>
        </header>
        <div class="space-y-7 p-6 sm:p-9">
            <div class="grid gap-5 sm:grid-cols-2">
                <section class="rounded-2xl bg-paper p-4"><h2 class="mb-3 flex items-center gap-2 text-sm font-semibold"><x-icon name="calendar" :size="18" />Simpan waktunya</h2><p class="text-sm leading-6">{{ $event->event_date->timezone('Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y') }}<br>{{ $event->event_date->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>@if($event->ends_at)<p class="mt-3 text-xs leading-5 text-muted">Selesai {{ $event->ends_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') }} WIB</p>@else<p class="mt-3 text-xs text-muted">Jam selesai belum dicatat pada agenda lama.</p>@endif</section>
                <section class="rounded-2xl bg-paper p-4"><h2 class="mb-3 flex items-center gap-2 text-sm font-semibold"><x-icon name="pin" :size="18" />Ketemu di mana?</h2><p class="break-words text-sm leading-6">{{ $event->location ?: 'Lokasi menyusul' }}</p>@if($event->link && in_array(strtolower(parse_url($event->link, PHP_URL_SCHEME) ?? ''), ['http', 'https']))<a href="{{ $event->link }}" target="_blank" rel="noopener noreferrer" class="quiet-button mt-3 px-0 text-xs">Buka tautan kegiatan ↗</a>@endif</section>
            </div>
            <section><h2 class="mb-3 text-lg font-bold">Tentang rencana ini</h2><p class="whitespace-pre-wrap break-words text-sm leading-7 text-muted">{{ $event->description ?: 'Detail kegiatan menyusul. Pantau informasi dari pengurus kelas, ya.' }}</p></section>
            <section class="border-t border-line pt-6" aria-labelledby="attendance-heading">
                <div class="flex flex-wrap items-center justify-between gap-3"><h2 id="attendance-heading" class="text-lg font-bold">Kamu ikut, kan?</h2><p class="rounded-full bg-sage px-3 py-1.5 text-xs font-semibold text-[#526443]">{{ $event->going_count }} teman konfirmasi hadir</p></div>
                @if($choice)<p class="mt-4 text-sm" role="status">Pilihanmu: <strong>{{ ['going' => 'Hadir', 'maybe' => 'Mungkin', 'not_going' => 'Tidak hadir'][$choice] }}</strong></p>@endif
                @can('respond', $event)
                <p class="mt-3 text-sm leading-6 text-muted">Bantu pengurus menyiapkan acaranya. Pilihan bisa diubah sebelum kegiatan dimulai.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach(['going' => '🙌 Hadir', 'maybe' => '🤔 Mungkin', 'not_going' => '🙏 Tidak hadir'] as $value => $label)
                    <button type="button" wire:click="respond('{{ $value }}')" wire:loading.attr="disabled" aria-pressed="{{ $choice === $value ? 'true' : 'false' }}" class="{{ $choice === $value ? 'primary-button' : 'quiet-button border border-line bg-paper' }}">{{ $label }}</button>
                    @endforeach
                </div>
                @if($choice)<button type="button" wire:click="respond('clear')" wire:loading.attr="disabled" class="quiet-button mt-3 text-xs text-muted">Batalkan konfirmasi saya</button>@endif
                <p wire:loading wire:target="respond" role="status" class="mt-3 text-xs text-muted">Menyimpan pilihan…</p>
                @else
                    @if($event->status === 'cancelled')
                    <p class="mt-4 rounded-xl bg-red-50 p-4 text-sm leading-6 text-red-800">Kegiatan ini dibatalkan. Konfirmasi kehadiran ditutup; pilihan sebelumnya hanya tersimpan sebagai catatan.</p>
                    @elseif($event->status !== 'upcoming')
                    <p class="mt-4 text-sm leading-6 text-muted">Konfirmasi kehadiran sudah ditutup karena kegiatan telah dimulai.</p>
                    @else
                    <p class="mt-4 text-sm text-muted">Verifikasi email untuk mengisi konfirmasi kehadiran.</p><a href="{{ route('verification.notice') }}" class="quiet-button mt-2">Verifikasi email</a>
                    @endif
                @endcan
            </section>
        </div>
    </article>
</div>
