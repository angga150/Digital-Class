<?php

namespace App\Livewire\Agenda;

use App\Models\Event;
use App\Support\CurrentClass;
use Livewire\Component;

class NextEvent extends Component
{
    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $event = Event::whereNull('cancelled_at')
            ->where(fn ($query) => $query->where('ends_at', '>', now())->orWhere(fn ($query) => $query->whereNull('ends_at')->where('event_date', '>', now())))
            ->orderBy('event_date')->orderBy('id')->first();

        return view('livewire.agenda.next-event', compact('event'));
    }
}
