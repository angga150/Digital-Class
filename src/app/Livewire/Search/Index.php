<?php

namespace App\Livewire\Search;

use App\Models\ClassMember;
use App\Models\Event;
use App\Models\Poll;
use App\Models\Post;
use App\Support\CurrentClass;
use App\Support\MemberProfile;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.member')]
#[Title('Cari di Kelas — Digital Class')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $q = '';

    #[Url]
    public string $category = 'stories';

    public function search(): void
    {
        $this->q = trim($this->q);
        $this->resetPage();
        $this->validate(['q' => 'required|string|min:2|max:100'], [
            'q.required' => 'Tulis kata yang ingin dicari.', 'q.min' => 'Gunakan minimal 2 karakter.',
            'q.max' => 'Kata pencarian maksimal 100 karakter.',
        ]);
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $term = trim($this->q);
        $results = null;
        $cards = collect();
        $valid = mb_strlen($term) >= 2 && mb_strlen($term) <= 100;
        if ($valid) {
            // Explicit escape works on SQLite and MySQL; % and _ stay literal.
            $pattern = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_strtolower($term)).'%';
            $match = fn ($query, string $column) => $query->whereRaw('LOWER('.$column.") LIKE ? ESCAPE '!'", [$pattern]);
            $query = match ($this->category) {
                'members' => ClassMember::with('user.profile')->whereHas('user', fn ($query) => $match($query, 'name')),
                'events' => Event::where(fn ($query) => $match($query, 'title')->orWhere(fn ($query) => $match($query, 'description'))),
                'polls' => Poll::where(fn ($query) => $match($query, 'question')->orWhere(fn ($query) => $match($query, 'description'))),
                default => $match(Post::query(), 'content')->where('type', $this->category === 'announcements' ? '=' : '!=', 'announcement'),
            };
            $results = $query->orderByDesc('id')->paginate(10);
            $cards = $results->getCollection()->map(function ($item) {
                if ($item instanceof ClassMember) {
                    $profile = MemberProfile::present($item, auth()->user());

                    return ['id' => $item->id, 'title' => $profile['name'], 'excerpt' => $item->user->name, 'meta' => 'Teman kelas', 'url' => route('members.show', $item)];
                }
                if ($item instanceof Event) {
                    return ['id' => $item->id, 'title' => $item->title, 'excerpt' => Str::limit($item->description ?? '', 220), 'meta' => $item->status_label.' · '.$item->event_date->timezone('Asia/Jakarta')->format('d/m/Y H:i').' WIB', 'url' => route('events.show', $item)];
                }
                if ($item instanceof Poll) {
                    return ['id' => $item->id, 'title' => $item->question, 'excerpt' => Str::limit($item->description ?? '', 220), 'meta' => $item->isOpen() ? 'Polling dibuka' : 'Polling ditutup', 'url' => route('polls.show', $item->id)];
                }

                return ['id' => $item->id, 'title' => Str::limit($item->content, 85), 'excerpt' => Str::limit($item->content, 220), 'meta' => $item->type === 'announcement' ? 'Pengumuman kelas' : 'Cerita kelas', 'url' => $item->type === 'announcement' ? route('announcements.show', $item) : route('feed.show', $item->id)];
            });
        }

        return view('livewire.search.index', compact('results', 'cards', 'valid'));
    }
}
