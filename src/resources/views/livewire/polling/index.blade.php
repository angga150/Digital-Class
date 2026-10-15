<div wire:poll.60s>
    <section class="paper-grid mb-7 rounded-3xl border border-line bg-[#fff0d7] p-6 sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-5">
            <div><p class="text-[10px] font-bold uppercase tracking-widest text-orange-dark">Satu kelas, banyak ide</p><h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Suaramu ikut menentukan.</h1><p class="mt-3 text-sm leading-6 text-muted">Dari tempat kumpul sampai rencana kelas. Yuk, pilih bareng.</p></div>
            @can('create', \App\Models\Poll::class)<button type="button" wire:click="create" class="primary-button"><x-icon name="chart" :size="18" />Buat polling</button>@endcan
        </div>
    </section>
    @if($notice)<p role="status" class="mb-5 rounded-2xl bg-sage p-4 text-sm">{{ $notice }}</p>@endif
    @if($errors->any())<div role="alert" class="mb-5 rounded-2xl bg-red-50 p-4 text-sm text-red-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    @if($formOpen)
    <form wire:submit="save" class="card mb-7 space-y-5 p-5 sm:p-7" aria-labelledby="poll-form-heading">
        <div class="flex items-center justify-between gap-3"><h2 id="poll-form-heading" class="text-xl font-bold">Apa yang ingin kita putuskan?</h2><button type="button" wire:click="closeForm" class="icon-button" aria-label="Tutup form polling"><x-icon name="close" /></button></div>
        <x-form.input name="question" label="Pertanyaan" wire:model="question" required maxlength="200" placeholder="Misalnya: mau kumpul di mana Jumat ini?" />
        <div><label for="poll-description" class="mb-2 block text-sm font-semibold">Catatan tambahan · opsional</label><textarea id="poll-description" wire:model="description" maxlength="2000" rows="3" class="field" placeholder="Beri sedikit konteks untuk teman-teman."></textarea></div>
        <fieldset class="space-y-3"><legend class="mb-3 text-sm font-semibold">Pilihan jawaban · 2 sampai 6 opsi</legend>
            @foreach($options as $index => $option)
            <div wire:key="poll-option-{{ $index }}" class="flex items-end gap-2"><div class="min-w-0 flex-1"><x-form.input :name="'options.'.$index" :label="'Pilihan '.($index + 1)" wire:model="options.{{ $index }}" required maxlength="150" /></div>@if(count($options) > 2)<button type="button" wire:click="removeOption({{ $index }})" class="quiet-button text-xs" aria-label="Hapus pilihan {{ $index + 1 }}">Hapus</button>@endif</div>
            @endforeach
            @if(count($options) < 6)<button type="button" wire:click="addOption" class="quiet-button text-sm">+ Tambah pilihan</button>@endif
        </fieldset>
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.input name="ends" label="Batas waktu · WIB" type="datetime-local" wire:model="ends" max="2038-01-19T10:14" required />
            <div><label for="poll-visibility" class="mb-2 block text-sm font-semibold">Kapan hasil terlihat?</label><select id="poll-visibility" wire:model="visibility" class="field"><option value="after_vote">Setelah memilih atau polling ditutup</option><option value="all">Langsung untuk semua anggota</option><option value="closed">Setelah polling ditutup</option><option value="admin_only">Hanya pengurus kelas</option></select></div>
        </div>
        <p class="rounded-xl bg-paper p-4 text-xs leading-6 text-muted">Satu akun, satu pilihan. Suara tidak bisa diubah. Nama pemilih tidak ditampilkan, termasuk kepada pengurus. Pertanyaan dan opsi tidak dapat diedit setelah terbit.</p>
        <div class="flex justify-end gap-2"><button type="button" wire:click="closeForm" class="quiet-button">Batal</button><button type="submit" wire:loading.attr="disabled" wire:target="save" class="primary-button"><span wire:loading.remove wire:target="save">Terbitkan polling</span><span wire:loading wire:target="save">Menyimpan…</span></button></div>
    </form>
    @endif
    <div class="mb-6 flex flex-wrap gap-2" aria-label="Filter polling">@foreach(['open' => 'Masih dibuka', 'closed' => 'Sudah ditutup', 'all' => 'Semua'] as $value => $label)<button type="button" wire:click="$set('filter', '{{ $value }}')" aria-pressed="{{ $filter === $value ? 'true' : 'false' }}" class="quiet-button {{ $filter === $value ? 'bg-orange/10' : 'text-muted' }}">{{ $label }}</button>@endforeach</div>
    <div class="grid items-start gap-5 lg:grid-cols-2">
        @forelse($polls as $poll)
            <livewire:polling.ballot :poll-id="$poll->id" :key="'poll-'.$poll->id" />
        @empty
            <div class="card col-span-full p-10 text-center"><p class="text-4xl" aria-hidden="true">💬</p><h2 class="mt-4 text-lg font-bold">Belum ada suara yang perlu dipilih.</h2><p class="mt-2 text-sm leading-6 text-muted">Coba filter lain, atau tunggu pengurus membuka polling baru.</p></div>
        @endforelse
    </div>
    <div class="mt-7">{{ $polls->links() }}</div>
</div>
