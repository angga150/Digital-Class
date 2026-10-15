<article class="card min-w-0 p-5 sm:p-6" wire:poll.60s aria-labelledby="poll-heading-{{ $pollId }}">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2"><span class="text-[10px] font-bold uppercase tracking-widest text-orange-dark">Suara kelas</span><span class="rounded-full bg-sage px-3 py-1 text-xs font-semibold text-[#526443]">{{ $poll->isOpen() ? 'Masih dibuka' : 'Ditutup' }}</span></div>
    <h2 id="poll-heading-{{ $pollId }}" class="break-words text-lg font-bold leading-7">{{ $poll->question }}</h2>
    @if($poll->description)<p class="mt-3 whitespace-pre-wrap break-words text-sm leading-6 text-muted">{{ $poll->description }}</p>@endif
    <p class="mt-3 text-xs leading-5 text-muted">
        @if($poll->ends_at)
            Batas waktu {{ $poll->ends_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j M Y, H:i') }} WIB
        @else
            Tanpa batas waktu · ditutup oleh pengurus
        @endif
    </p>
    <p class="mt-2 text-xs leading-5 text-muted">Pilihan pribadi. Nama pemilih tidak ditampilkan.</p>
    @if($notice)<p role="status" class="mt-4 rounded-xl bg-sage p-3 text-sm">{{ $notice }}</p>@endif
    @error('choice')<p role="alert" class="mt-4 text-sm text-red-800">{{ $message }}</p>@enderror
    @if($ownChoice)
        <p class="mt-4 rounded-xl bg-paper p-3 text-sm">Suaramu tersimpan: <strong class="break-words">{{ $options->firstWhere('id', $ownChoice)?->option_text ?? 'Pilihan tercatat' }}</strong><span class="mt-1 block text-xs text-muted">Pilihan tidak dapat diubah.</span></p>
    @elseif($poll->isOpen())
        @can('vote', $poll)
        <form wire:submit="vote" class="mt-5">
            <fieldset class="space-y-2"><legend class="mb-3 text-sm font-semibold">Pilih satu jawaban</legend>
                @foreach($options as $option)<label wire:key="answer-{{ $option->id }}" class="flex min-h-12 cursor-pointer items-start gap-3 rounded-xl border border-line bg-paper/50 p-3 text-sm hover:bg-orange/5"><input type="radio" wire:model="choice" value="{{ $option->id }}" name="poll-{{ $pollId }}" required class="mt-1 size-4 shrink-0 accent-orange-dark"><span class="min-w-0 break-words leading-6">{{ $option->option_text }}</span></label>@endforeach
            </fieldset>
            <p class="mt-3 text-xs leading-5 text-muted">Pastikan pilihanmu. Suara hanya bisa dikirim satu kali.</p>
            <button type="submit" wire:loading.attr="disabled" wire:target="vote" class="primary-button mt-4 text-sm"><span wire:loading.remove wire:target="vote">Kirim suara</span><span wire:loading wire:target="vote">Menyimpan…</span><x-icon name="arrow" :size="15" /></button>
        </form>
        @else<p class="mt-4 text-sm text-muted">Verifikasi email untuk ikut memilih.</p><a href="{{ route('verification.notice') }}" class="quiet-button mt-2 text-xs">Verifikasi email</a>@endcan
    @else
        <p class="mt-4 text-sm text-muted">{{ $poll->allow_multiple ? 'Polling lama dengan pilihan ganda belum didukung. Pengurus dapat membuat polling satu pilihan yang baru.' : 'Waktu memilih sudah selesai.' }}</p>
    @endif
    @if($canSeeResults)
        <section class="mt-6 space-y-4 border-t border-line pt-5" aria-label="Hasil polling">
            <div class="flex items-center justify-between gap-3"><h3 class="text-sm font-bold">Hasil suara</h3><span class="text-xs text-muted">{{ $total }} suara</span></div>
            @foreach($options as $option)
                @php($percent = $total > 0 ? round($option->votes_count / $total * 100, 1) : 0)
                <div><div class="mb-2 flex justify-between gap-3 text-xs leading-5"><span class="min-w-0 break-words">{{ $option->option_text }}</span><span class="shrink-0 font-semibold">{{ $option->votes_count }} · {{ $percent }}%</span></div><div class="h-2.5 overflow-hidden rounded-full bg-paper" role="meter" aria-label="{{ $option->option_text }}" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"><div class="h-full rounded-full bg-orange" style="width: {{ $percent }}%"></div></div></div>
            @endforeach
        </section>
    @else
        <p class="mt-5 rounded-xl bg-paper p-3 text-xs leading-6 text-muted">{{ match($poll->result_visibility) { 'admin_only' => 'Hasil hanya dapat dilihat pengurus kelas.', 'closed' => 'Hasil akan terlihat setelah polling ditutup.', default => 'Hasil akan terlihat setelah kamu memilih atau polling ditutup.' } }}</p>
    @endif
    @can('close', $poll)<div class="mt-5 border-t border-line pt-3"><button type="button" wire:click="close" wire:confirm="Tutup polling sekarang? Suara yang sudah masuk tetap tersimpan dan polling tidak bisa dibuka ulang." wire:loading.attr="disabled" class="quiet-button text-xs text-muted">Tutup polling</button></div>@endcan
</article>
