<?php

namespace App\Livewire\Birthdays;

use App\Support\ClassBirthdays;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.member')]
#[Title('Kalender Ulang Tahun — Digital Class')]
class Index extends Component
{
    public string $month = 'all';

    public function render()
    {
        $birthdays = ClassBirthdays::upcoming(auth()->user());
        $todayCount = $birthdays->where('days', 0)->count();
        $soonCount = $birthdays->where('days', '<=', 30)->count();
        if (in_array($this->month, array_map('strval', range(1, 12)), true)) {
            $birthdays = $birthdays->where('month', (int) $this->month)->values();
        }

        return view('livewire.birthdays.index', compact('birthdays', 'todayCount', 'soonCount'));
    }
}
