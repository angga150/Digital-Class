<?php

namespace App\Livewire\Polling;

use App\Models\Poll;
use App\Support\CurrentClass;
use Livewire\Component;

class Featured extends Component
{
    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $poll = Poll::where('is_active', true)->where('allow_multiple', false)
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->orderByRaw('ends_at IS NULL')->orderBy('ends_at')->orderByDesc('id')->first();

        return view('livewire.polling.featured', compact('poll'));
    }
}
