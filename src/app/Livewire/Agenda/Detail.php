<?php

namespace App\Livewire\Agenda;

use App\Models\Event;
use App\Support\CurrentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.member')]
#[Title('Detail Agenda — Digital Class')]
class Detail extends Component
{
    #[Locked]
    public int $eventId;

    #[Locked]
    public string $notice = '';

    public function mount(Event $event): void
    {
        Gate::authorize('view', $event);
        $this->eventId = $event->id;
    }

    public function respond(string $choice): void
    {
        validator(['choice' => $choice], ['choice' => ['required', Rule::in(['going', 'maybe', 'not_going', 'clear'])]])->validate();
        DB::transaction(function () use ($choice) {
            $event = Event::lockForUpdate()->findOrFail($this->eventId);
            Gate::authorize('respond', $event);
            if ($choice === 'clear') {
                $event->rsvps()->where('user_id', auth()->id())->delete();
            } else {
                $event->rsvps()->updateOrCreate(['user_id' => auth()->id()], ['status' => $choice]);
            }
            $event->update(['rsvp_count' => $event->rsvps()->where('status', 'going')->count()]);
        });
        $this->notice = $choice === 'clear' ? 'Konfirmasi kehadiranmu dibatalkan.' : 'Pilihanmu tersimpan. Kamu bisa mengubahnya sebelum acara dimulai.';
    }

    public function render()
    {
        abort_unless(CurrentClass::membership(), 403);
        $event = Event::withCount(['rsvps as going_count' => fn ($query) => $query->where('status', 'going')])->findOrFail($this->eventId);
        Gate::authorize('view', $event);
        $choice = $event->rsvps()->where('user_id', auth()->id())->value('status');

        return view('livewire.agenda.detail', compact('event', 'choice'));
    }
}
