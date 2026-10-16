<?php

namespace App\Livewire\Stories;

use App\Models\ClassMember;
use App\Models\Post;
use App\Models\PostComment;
use App\Support\CurrentClass;
use App\Support\MemberProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.member')]
#[Title('Cerita Kelas — Digital Class')]
class Feed extends Component
{
    public string $body = '';

    public string $editBody = '';

    public string $commentBody = '';

    #[Locked]
    public ?int $editing = null;

    #[Locked]
    public ?int $discussion = null;

    #[Locked]
    public int $limit = 10;

    #[Locked]
    public int $commentLimit = 10;

    #[Url]
    public string $filter = 'all';

    #[Locked]
    public ?int $focusedPost = null;

    public function mount(?int $postId = null): void
    {
        if ($postId !== null) {
            $this->focusedPost = Post::findOrFail($postId)->id;
        }
    }

    public function setBookmark(int $id, bool $saved): void
    {
        DB::transaction(function () use ($id, $saved) {
            $post = Post::lockForUpdate()->findOrFail($id);
            Gate::authorize('interact', $post);
            if ($saved) {
                $post->bookmarks()->firstOrCreate(['user_id' => auth()->id()]);
            } else {
                $post->bookmarks()->where('user_id', auth()->id())->delete();
            }
        });
        $this->notice = $saved ? 'Cerita tersimpan untuk kamu baca lagi. Hanya kamu yang melihat daftar ini.' : 'Cerita dilepas dari simpananmu.';
    }

    #[Locked]
    public string $notice = '';

    public function updatedFilter(): void
    {
        $this->reset('limit', 'discussion', 'commentBody', 'commentLimit');
    }

    private function throttle(string $action, int $attempts): void
    {
        $key = 'stories:'.$action.':'.auth()->id();
        if (RateLimiter::tooManyAttempts($key, $attempts)) {
            throw ValidationException::withMessages(['body' => 'Pelan-pelan, ya. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.']);
        }
        RateLimiter::hit($key, 60);
    }

    private function validateText(string $field, int $max): string
    {
        $this->$field = trim($this->$field);
        $this->validate([$field => ['required', 'string', 'max:'.$max]], [
            'required' => 'Tulis sesuatu dulu, ya.',
            'max' => 'Tulisan maksimal :max karakter.',
        ]);

        return $this->$field;
    }

    public function publish(): void
    {
        Gate::authorize('create', Post::class);
        $content = $this->validateText('body', 2000);
        $this->throttle('publish', 5);
        Post::create([
            'class_id' => CurrentClass::membership()->class_id,
            'user_id' => auth()->id(),
            'content' => $content,
            'type' => 'discussion',
        ]);
        $this->reset('body', 'limit');
        $this->notice = 'Ceritamu sudah dibagikan ke kelas. Terima kasih sudah bercerita!';
    }

    public function edit(int $id): void
    {
        $post = Post::findOrFail($id);
        Gate::authorize('update', $post);
        $this->editing = $post->id;
        $this->editBody = $post->content;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->reset('editing', 'editBody');
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $content = $this->validateText('editBody', 2000);
        DB::transaction(function () use ($content) {
            $post = Post::lockForUpdate()->findOrFail($this->editing);
            Gate::authorize('update', $post);
            $post->update(['content' => $content]);
        });
        $this->cancelEdit();
        $this->notice = 'Cerita berhasil diperbarui.';
    }

    public function deletePost(int $id): void
    {
        DB::transaction(function () use ($id) {
            $post = Post::lockForUpdate()->findOrFail($id);
            Gate::authorize('delete', $post);
            $post->delete();
        });
        if ($this->discussion === $id) {
            $this->reset('discussion', 'commentBody');
        }
        if ($this->editing === $id) {
            $this->cancelEdit();
        }
        $this->notice = 'Cerita dihapus dari feed. Datanya tetap tersimpan untuk pemulihan.';
        if ($this->focusedPost === $id) {
            $this->focusedPost = null;
            $this->redirectRoute('feed');
        }
    }

    public function setLike(int $id, bool $liked): void
    {
        DB::transaction(function () use ($id, $liked) {
            $post = Post::lockForUpdate()->findOrFail($id);
            Gate::authorize('interact', $post);
            if ($liked) {
                $post->likes()->firstOrCreate(['user_id' => auth()->id()]);
            } else {
                $post->likes()->where('user_id', auth()->id())->delete();
            }
            $post->update(['likes_count' => $post->likes()->count()]);
        });
    }

    public function openComments(int $id): void
    {
        Post::findOrFail($id);
        $this->discussion = $this->discussion === $id ? null : $id;
        $this->reset('commentBody', 'commentLimit');
        $this->resetValidation();
    }

    public function addComment(): void
    {
        $content = $this->validateText('commentBody', 500);
        DB::transaction(function () use ($content) {
            $post = Post::lockForUpdate()->findOrFail($this->discussion);
            Gate::authorize('create', [PostComment::class, $post]);
            $this->throttle('comment', 15);
            $post->comments()->create(['user_id' => auth()->id(), 'content' => $content]);
            $post->update(['comments_count' => $post->comments()->count()]);
        });
        $this->reset('commentBody', 'commentLimit');
        $this->notice = 'Komentarmu sudah terkirim.';
    }

    public function deleteComment(int $id): void
    {
        DB::transaction(function () use ($id) {
            $comment = PostComment::findOrFail($id);
            $post = Post::lockForUpdate()->findOrFail($comment->post_id);
            Gate::authorize('delete', $comment);
            $comment->delete();
            $post->update(['comments_count' => $post->comments()->count()]);
        });
        $this->notice = 'Komentar dihapus.';
    }

    public function loadMore(): void
    {
        $this->limit += 10;
    }

    public function moreComments(): void
    {
        $this->commentLimit += 10;
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $query = Post::with('user')->withCount(['likes', 'comments'])
            ->withExists('bookmarks as bookmarked')
            ->withExists(['likes as liked' => fn ($query) => $query->where('user_id', auth()->id())]);
        if ($this->focusedPost) {
            Post::findOrFail($this->focusedPost);
            $query->whereKey($this->focusedPost);
        } elseif ($this->filter === 'saved') {
            $query->whereHas('bookmarks');
        } elseif ($this->filter === 'mine') {
            $query->where('user_id', auth()->id());
        }
        $posts = $query->orderByDesc('created_at')->orderByDesc('id')->limit($this->limit + 1)->get();
        $hasMore = $posts->count() > $this->limit;
        $posts = $posts->take($this->limit);
        $comments = collect();
        $moreComments = false;
        if ($this->discussion && $posts->contains('id', $this->discussion)) {
            $comments = PostComment::with('user')->where('post_id', $this->discussion)->latest('id')->limit($this->commentLimit + 1)->get();
            $moreComments = $comments->count() > $this->commentLimit;
            $comments = $comments->take($this->commentLimit);
        }
        $ids = $posts->pluck('user_id')->merge($comments->pluck('user_id'))->unique();
        $members = ClassMember::with('user.profile')->whereIn('user_id', $ids)->get();
        $authors = $members->mapWithKeys(fn ($member) => [$member->user_id => MemberProfile::present($member, auth()->user())]);

        return view('livewire.stories.feed', compact('posts', 'comments', 'authors', 'hasMore', 'moreComments'));
    }
}
