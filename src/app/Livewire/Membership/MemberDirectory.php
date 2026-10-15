<?php

namespace App\Livewire\Membership;

use App\Models\ClassMember;
use App\Support\MemberProfile;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.member')]
class MemberDirectory extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = mb_substr(trim($this->search), 0, 100);
        $query = ClassMember::with('user.profile')->orderBy('id');
        if ($search !== '') {
            // Search public names only; hidden profile fields must not be inferable through search.
            $query->whereHas('user', fn ($query) => $query->where('name', 'like', '%'.addcslashes($search, '%_\\').'%'));
        }
        $members = $query->paginate(12);
        $cards = $members->getCollection()->map(fn ($member) => MemberProfile::present($member, auth()->user()));

        return view('livewire.membership.member-directory', compact('members', 'cards'));
    }
}
