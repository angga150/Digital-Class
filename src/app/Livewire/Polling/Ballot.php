<?php

namespace App\Livewire\Polling;

use App\Models\Poll;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Ballot extends Component
{
    #[Locked]
    public int $pollId;

    public ?int $choice = null;

    #[Locked]
    public string $notice = '';

    public function mount(int $pollId): void
    {
        $poll = Poll::findOrFail($pollId);
        Gate::authorize('view', $poll);
        $this->pollId = $poll->id;
    }

    public function vote(): void
    {
        $this->validate(['choice' => 'required|integer'], ['choice.required' => 'Pilih satu jawaban dulu, ya.']);
        DB::transaction(function () {
            $poll = Poll::lockForUpdate()->findOrFail($this->pollId);
            Gate::authorize('vote', $poll);
            if ($poll->hasVoted(auth()->user())) {
                $this->addError('choice', 'Suaramu sudah tercatat dan tidak dapat diubah.');

                return;
            }
            $option = $poll->options()->find($this->choice);
            if (! $option) {
                $this->addError('choice', 'Pilih jawaban yang tersedia di polling ini.');

                return;
            }
            $poll->votes()->create(['user_id' => auth()->id(), 'poll_option_id' => $option->id]);
            $poll->update(['total_votes' => $poll->votes()->count()]);
            $option->update(['votes_count' => $option->votes()->count()]);
            $this->notice = 'Terima kasih! Suaramu sudah tersimpan.';
            $this->choice = null;
        });
    }

    public function close(): void
    {
        DB::transaction(function () {
            $poll = Poll::lockForUpdate()->findOrFail($this->pollId);
            Gate::authorize('close', $poll);
            $poll->update(['is_active' => false]);
        });
        $this->notice = 'Polling ditutup. Suara yang sudah masuk tetap tersimpan.';
    }

    public function render()
    {
        $poll = Poll::findOrFail($this->pollId);
        Gate::authorize('view', $poll);
        $ownChoice = $poll->votes()->where('user_id', auth()->id())->value('poll_option_id');
        $canSeeResults = Gate::allows('viewResults', $poll);
        // Only aggregate counts reach the view; never load voter identities.
        $options = $poll->options()->select(['id', 'poll_id', 'option_text', 'order']);
        if ($canSeeResults) {
            $options->withCount('votes');
        }
        $options = $options->get();
        $total = $canSeeResults ? $options->sum('votes_count') : null;

        return view('livewire.polling.ballot', compact('poll', 'options', 'ownChoice', 'canSeeResults', 'total'));
    }
}
