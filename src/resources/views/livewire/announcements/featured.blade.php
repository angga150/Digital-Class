<section class="mb-5" wire:poll.60s aria-label="Pengumuman kelas terpilih">
    <a href="{{ $post ? route('announcements.show', $post) : route('announcements') }}" class="flex w-full items-center gap-4 rounded-2xl border border-[#dce7ce] bg-sage px-4 py-4 text-left transition hover:bg-[#e1ebd6] sm:px-5">
        <span class="shrink-0 text-[#547240]"><x-icon name="megaphone" :size="25" /></span>
        <span class="min-w-0 flex-1"><span class="mb-0.5 block text-[10px] font-bold uppercase tracking-widest text-[#59734a]">Papan kabar {{ $post?->is_pinned ? '· Disematkan' : '' }}</span><span class="block break-words text-sm font-semibold">{{ $post ? \Illuminate\Support\Str::limit($post->content, 140) : 'Belum ada pengumuman. Kabar kelas akan muncul di sini.' }}</span></span>
        <span class="hidden shrink-0 text-xs text-[#59734a] sm:block">{{ $post ? 'Baca info' : 'Buka papan' }}</span><x-icon name="chevron" :size="17" class="shrink-0 text-[#59734a]" />
    </a>
</section>
