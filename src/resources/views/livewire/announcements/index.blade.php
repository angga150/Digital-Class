<div wire:poll.60s>
    <section class="paper-grid mb-7 rounded-3xl border border-[#dce7ce] bg-sage p-6 sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-5"><div><p class="text-[10px] font-bold uppercase tracking-widest text-[#526443]">Biar kabarnya nggak tenggelam</p><h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Papan kabar kelas.</h1><p class="mt-3 text-sm leading-6 text-muted">Info penting dari pengurus, tersimpan di satu tempat.</p></div>@can('publishAnnouncement', \App\Models\Post::class)<button type="button" wire:click="create" class="primary-button"><x-icon name="megaphone" :size="18" />Tulis pengumuman</button>@endcan</div>
    </section>
    @if($notice)<p role="status" class="mb-5 rounded-2xl bg-sage p-4 text-sm">{{ $notice }}</p>@endif
    @if($errors->any())<div role="alert" class="mb-5 rounded-2xl bg-red-50 p-4 text-sm text-red-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    @if($formOpen)
    <form wire:submit="save" class="card mb-7 space-y-5 p-5 sm:p-7">
        <div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold">{{ $editing ? 'Perbarui kabar kelas' : 'Ada kabar penting apa?' }}</h2><button type="button" wire:click="closeForm" class="icon-button" aria-label="Tutup form pengumuman"><x-icon name="close" /></button></div>
        <div><label for="announcement-body" class="mb-2 block text-sm font-semibold">Isi pengumuman</label><textarea id="announcement-body" wire:model="body" rows="6" required maxlength="2000" class="field" placeholder="Tulis info utamanya di awal, lalu detail yang perlu diketahui teman sekelas."></textarea><p class="mt-2 text-xs text-muted">Maksimal 2.000 karakter. Awal tulisan menjadi ringkasan di dashboard.</p></div>
        <label class="flex items-start gap-3 rounded-xl bg-paper p-4 text-sm"><input type="checkbox" wire:model="pinned" class="mt-1 size-4 accent-orange-dark"><span>Sematkan di papan kabar<span class="mt-1 block text-xs leading-5 text-muted">Pengumuman yang disematkan diprioritaskan pada banner dashboard. Jika ada beberapa, yang paling baru diterbitkan tampil lebih dulu.</span></span></label>
        <div class="flex justify-end gap-2"><button type="button" wire:click="closeForm" class="quiet-button">Batal</button><button type="submit" wire:loading.attr="disabled" wire:target="save" class="primary-button"><span wire:loading.remove wire:target="save">{{ $editing ? 'Simpan perubahan' : 'Terbitkan kabar' }}</span><span wire:loading wire:target="save">Menyimpan…</span></button></div>
    </form>
    @endif
    <div class="mb-6 flex gap-2" aria-label="Filter pengumuman">@foreach(['all' => 'Semua kabar', 'pinned' => 'Disematkan'] as $value => $label)<button type="button" wire:click="$set('filter', '{{ $value }}')" aria-pressed="{{ $filter === $value ? 'true' : 'false' }}" class="quiet-button {{ $filter === $value ? 'bg-orange/10' : 'text-muted' }}">{{ $label }}</button>@endforeach</div>
    <div class="grid items-start gap-5 lg:grid-cols-2">
        @forelse($announcements as $post)
        <article wire:key="announcement-{{ $post->id }}" class="card min-w-0 p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-2"><h2 class="flex items-center gap-2 text-sm font-bold"><x-icon name="megaphone" :size="18" class="text-orange-dark" />Pengumuman kelas</h2>@if($post->is_pinned)<span class="rounded-full bg-sage px-3 py-1 text-xs text-[#526443]">Disematkan</span>@endif</div>
            <p class="mt-3 text-xs text-muted">{{ $post->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') }} WIB</p>
            <p class="mt-5 whitespace-pre-wrap break-words text-sm leading-7">{{ $post->content }}</p>
            <div class="mt-5 border-t border-line pt-3"><a href="{{ route('announcements.show', $post) }}" class="quiet-button px-0 text-xs">Buka pengumuman <x-icon name="arrow" :size="15" /></a></div>
            @can('manageAnnouncement', $post)<div class="mt-3 flex flex-wrap gap-1 border-t border-line pt-3"><button type="button" wire:click="edit({{ $post->id }})" class="quiet-button text-xs">Edit</button><button type="button" wire:click="pin({{ $post->id }}, {{ $post->is_pinned ? 'false' : 'true' }})" wire:loading.attr="disabled" class="quiet-button text-xs">{{ $post->is_pinned ? 'Lepas sematan' : 'Sematkan' }}</button><button type="button" wire:click="delete({{ $post->id }})" wire:confirm="Hapus pengumuman ini dari papan kabar dan cerita kelas? Catatan tetap tersimpan untuk pemulihan administratif." wire:loading.attr="disabled" class="quiet-button text-xs text-muted">Hapus</button></div>@endcan
        </article>
        @empty
        <div class="card col-span-full p-10 text-center"><p class="text-4xl" aria-hidden="true">📌</p><h2 class="mt-4 text-lg font-bold">Papan kabarnya masih kosong.</h2><p class="mt-2 text-sm leading-6 text-muted">Coba filter lain. Kabar baru dari pengurus akan tersimpan di sini.</p></div>
        @endforelse
    </div>
    <div class="mt-7">{{ $announcements->links() }}</div>
</div>
