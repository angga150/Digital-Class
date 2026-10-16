<section id="cerita" class="mx-auto w-full min-w-0 max-w-3xl scroll-mt-24" aria-labelledby="stories-heading">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div><p class="text-[10px] font-bold uppercase tracking-[.18em] text-orange-dark">Dari teman, untuk teman</p><h2 id="stories-heading" class="mt-2 text-2xl font-bold">Cerita kelas <span class="text-orange">✳</span></h2></div>
        <a href="{{ route('feed') }}" class="quiet-button text-xs">Ruang cerita <x-icon name="arrow" :size="16" /></a>
    </div>
    @if($notice)<p role="status" class="mb-4 rounded-2xl border border-[#d6e3c7] bg-sage p-4 text-sm leading-6 text-[#435834]">{{ $notice }}</p>@endif
    @if($errors->any())
        <div role="alert" class="mb-4 rounded-2xl bg-red-50 p-4 text-sm text-red-800">@foreach($errors->all() as $message)<p>{{ $message }}</p>@endforeach</div>
    @endif
    <div id="story-compose" class="card mb-6 scroll-mt-24 p-5 sm:p-6">
        @if(auth()->user()->hasVerifiedEmail())
        <form wire:submit="publish">
            <div class="mb-4 flex items-center gap-3"><x-avatar :initials="auth()->user()->initials" /><div><label for="story-body" class="text-sm font-semibold">Ada cerita apa, {{ auth()->user()->display_name }}?</label><p class="mt-1 text-xs text-muted">Hanya teman satu kelas yang bisa membaca.</p></div></div>
            <textarea id="story-body" wire:model="body" required maxlength="2000" rows="3" class="field resize-y bg-paper" placeholder="Kabar kecil, pertanyaan tugas, atau momen hari ini…" aria-describedby="story-help"></textarea>
            <div class="mt-3 flex flex-wrap items-center justify-between gap-3"><p id="story-help" class="text-xs text-muted">Cerita teks · maksimal 2.000 karakter</p><button type="submit" class="primary-button" wire:loading.attr="disabled" wire:target="publish"><x-icon name="pen" :size="16" /><span wire:loading.remove wire:target="publish">Bagikan cerita</span><span wire:loading wire:target="publish">Mengirim…</span></button></div>
        </form>
        @else
        <p class="font-semibold">Cerita pertamamu ditunggu 🌿</p><p class="mt-2 text-sm leading-6 text-muted">Verifikasi email untuk menulis cerita, berkomentar, dan memberi suka. Kamu tetap bisa membaca cerita kelas.</p><a href="{{ route('verification.notice') }}" class="quiet-button mt-3">Verifikasi email <x-icon name="arrow" :size="16" /></a>
        @endif
    </div>
    <div class="mb-5 flex items-center gap-2" aria-label="Filter cerita">
        <button type="button" wire:click="$set('filter', 'all')" aria-pressed="{{ $filter !== 'mine' ? 'true' : 'false' }}" class="quiet-button {{ $filter !== 'mine' ? 'bg-orange/10' : 'text-muted' }}">Semua cerita</button>
        <button type="button" wire:click="$set('filter', 'mine')" aria-pressed="{{ $filter === 'mine' ? 'true' : 'false' }}" class="quiet-button {{ $filter === 'mine' ? 'bg-orange/10' : 'text-muted' }}">Ceritaku</button>
        <span wire:loading.delay wire:target="filter,loadMore" class="ml-auto text-xs text-muted" role="status">Memuat…</span>
    </div>
    <div class="space-y-5">
        @forelse($posts as $post)
        @php($author = $authors->get($post->user_id))
        <article wire:key="story-{{ $post->id }}" class="card overflow-hidden">
            @if($post->type === 'announcement')<a href="{{ route('announcements.show', $post) }}" class="flex items-center gap-2 border-b border-line bg-sage px-5 py-3 text-xs font-semibold text-[#526443]"><x-icon name="megaphone" :size="15" />Pengumuman kelas {{ $post->is_pinned ? '· Disematkan' : '' }}<x-icon name="arrow" :size="14" /></a>@endif
            <div class="flex items-start gap-3 px-5 pt-5">
                <x-avatar :initials="$author['initials'] ?? mb_strtoupper(mb_substr($post->user->name, 0, 1))" />
                <div class="min-w-0 flex-1">
                    @if($author)<a href="{{ route('members.show', $author['id']) }}" class="break-words text-sm font-semibold hover:text-orange-dark">{{ $author['name'] }}</a>@else<p class="break-words text-sm font-semibold">{{ $post->user->name }}</p>@endif
                    <p class="mt-1 text-[11px] text-muted"><time datetime="{{ $post->created_at->toIso8601String() }}" title="{{ $post->created_at->timezone('Asia/Jakarta')->format('d M Y H:i').' WIB' }}">{{ $post->created_at->locale('id')->diffForHumans() }}</time> · Kelas kita</p>
                </div>
                @can('update', $post)<button type="button" wire:click="edit({{ $post->id }})" class="quiet-button px-2 text-xs" aria-label="Edit cerita {{ $post->id }}">Edit</button>@endcan
                @can('delete', $post)<button type="button" wire:click="deletePost({{ $post->id }})" wire:confirm="Hapus cerita ini dari feed kelas?" wire:loading.attr="disabled" class="quiet-button px-2 text-xs text-muted" aria-label="Hapus cerita {{ $post->id }}">Hapus</button>@endcan
            </div>
            @if($editing === $post->id)
                <form wire:submit="saveEdit" class="p-5"><label for="edit-story-{{ $post->id }}" class="mb-2 block text-sm font-semibold">Perbarui ceritamu</label><textarea id="edit-story-{{ $post->id }}" wire:model="editBody" required maxlength="2000" rows="4" class="field"></textarea><div class="mt-3 flex justify-end gap-2"><button type="button" wire:click="cancelEdit" class="quiet-button text-muted">Batal</button><button type="submit" class="primary-button" wire:loading.attr="disabled">Simpan cerita</button></div></form>
            @else
                <p class="whitespace-pre-wrap break-words px-5 py-5 text-sm leading-7">{{ $post->content }}</p>
            @endif
            <div class="flex items-center justify-between gap-2 border-t border-line px-4 py-2">
                <div class="flex gap-1">
                    <button type="button" wire:click="setLike({{ $post->id }}, {{ $post->liked ? 'false' : 'true' }})" wire:loading.attr="disabled" @disabled(!auth()->user()->hasVerifiedEmail()) aria-pressed="{{ $post->liked ? 'true' : 'false' }}" aria-label="{{ $post->liked ? 'Batal suka' : 'Suka' }} cerita {{ $post->id }}" class="quiet-button gap-2 px-3 {{ $post->liked ? 'text-orange-dark' : 'text-muted' }} disabled:opacity-50"><x-icon name="heart" :size="20" :class="$post->liked ? 'fill-orange/20' : ''" /><span>{{ $post->likes_count }}</span></button>
                    <button type="button" wire:click="openComments({{ $post->id }})" aria-expanded="{{ $discussion === $post->id ? 'true' : 'false' }}" aria-label="Komentar cerita {{ $post->id }}" class="quiet-button gap-2 px-3 text-muted"><x-icon name="comment" :size="19" /><span>{{ $post->comments_count }}</span></button>
                </div>
                <span class="text-[10px] text-muted">Bersama, lebih berwarna</span>
            </div>
            @if($discussion === $post->id)
            <section class="border-t border-line bg-paper/50 p-5" aria-label="Komentar">
                <h3 class="mb-4 text-sm font-semibold">Obrolan kecil</h3>
                @if(auth()->user()->hasVerifiedEmail())
                <form wire:submit="addComment" class="mb-5"><label for="comment-body-{{ $post->id }}" class="sr-only">Komentarmu</label><textarea id="comment-body-{{ $post->id }}" wire:model="commentBody" rows="2" maxlength="500" required class="field bg-white" placeholder="Tulis sesuatu yang baik…"></textarea><button type="submit" wire:loading.attr="disabled" class="primary-button mt-3 text-xs">Kirim komentar</button></form>
                @endif
                <div class="space-y-3">
                @forelse($comments as $comment)
                @php($commentAuthor = $authors->get($comment->user_id))
                <div wire:key="comment-{{ $comment->id }}" class="rounded-2xl border border-line bg-white p-4">
                    <div class="flex items-center justify-between gap-3"><p class="min-w-0 break-words text-xs font-semibold">{{ $commentAuthor['name'] ?? $comment->user->name }}</p>
                    @can('delete', $comment)<button type="button" wire:click="deleteComment({{ $comment->id }})" wire:confirm="Hapus komentar ini?" wire:loading.attr="disabled" class="quiet-button min-h-8 px-2 py-1 text-xs text-muted" aria-label="Hapus komentar {{ $comment->id }}">Hapus</button>@endcan</div>
                    <p class="mt-2 whitespace-pre-wrap break-words text-sm leading-6">{{ $comment->content }}</p><p class="mt-2 text-[10px] text-muted">{{ $comment->created_at->locale('id')->diffForHumans() }}</p>
                </div>
                @empty<p class="text-sm text-muted">Belum ada komentar. Mulai obrolannya, yuk!</p>@endforelse
                </div>
                @if($moreComments)<button type="button" wire:click="moreComments" wire:loading.attr="disabled" class="quiet-button mt-4 w-full">Komentar sebelumnya</button>@endif
            </section>
            @endif
        </article>
        @empty
        <div class="card p-8 text-center"><span class="text-3xl" aria-hidden="true">🌱</span><h3 class="mt-4 font-bold">{{ $filter === 'mine' ? 'Cerita pertamamu dimulai di sini.' : 'Ruang cerita masih sepi.' }}</h3><p class="mt-2 text-sm leading-6 text-muted">Nggak harus sesuatu yang besar. Kabar kecil hari ini juga layak dibagi.</p></div>
        @endforelse
    </div>
    @if($hasMore)<button type="button" wire:click="loadMore" wire:loading.attr="disabled" class="quiet-button mt-5 w-full border border-line bg-white">Muat cerita sebelumnya</button>@elseif($posts->isNotEmpty())<p class="py-6 text-center text-xs text-muted">Sudah sampai di cerita terakhir. Giliran kamu? ☺</p>@endif
</section>
