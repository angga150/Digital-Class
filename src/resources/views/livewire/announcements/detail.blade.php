<div class="mx-auto max-w-3xl" wire:poll.60s>
    <a href="{{ route('announcements') }}" class="quiet-button mb-5">← Semua kabar kelas</a>
    <article class="card overflow-hidden">
        <header class="paper-grid border-b border-line bg-sage p-6 sm:p-8"><p class="flex items-center gap-2 text-xs font-semibold text-[#526443]"><x-icon name="megaphone" :size="18" />Papan kabar kelas {{ $post->is_pinned ? '· Disematkan' : '' }}</p><h1 class="mt-4 text-3xl font-bold tracking-tight">Kabar untuk kita.</h1><p class="mt-3 text-xs text-muted">Diterbitkan {{ $post->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') }} WIB</p></header>
        <div class="p-6 sm:p-8"><p class="whitespace-pre-wrap break-words text-base leading-8">{{ $post->content }}</p>@if($post->updated_at->greaterThan($post->created_at))<p class="mt-6 border-t border-line pt-4 text-xs text-muted">Terakhir diperbarui {{ $post->updated_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') }} WIB</p>@endif</div>
    </article>
</div>
