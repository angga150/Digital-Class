<?php

namespace App\Livewire\Birthdays;

use App\Support\ClassBirthdays;
use Livewire\Component;

class Upcoming extends Component
{
    public function render()
    {
        $birthdays = ClassBirthdays::upcoming(auth()->user())->where('days', '<=', 30);
        $next = $birthdays->first();
        // Include everyone on the nearest celebration day, rather than truncating ties.
        $birthdays = $next ? $birthdays->where('date', $next['date'])->values() : collect();

        return view('livewire.birthdays.upcoming', compact('birthdays', 'next'));
    }
}
