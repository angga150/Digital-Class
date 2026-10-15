<?php

namespace App\Support;

use App\Models\ClassMember;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class ClassBirthdays
{
    public static function upcoming(User $viewer): Collection
    {
        $membership = CurrentClass::membership($viewer);
        abort_unless($membership, 403);
        $today = CarbonImmutable::now('Asia/Jakarta')->startOfDay();

        return ClassMember::with('user.profile')
            ->where('class_id', $membership->class_id)
            ->whereHas('user.profile', fn ($query) => $query->whereNotNull('birth_date'))
            ->get()->map(function ($member) use ($viewer, $today) {
                $profile = MemberProfile::present($member, $viewer);
                // Privacy is resolved before deriving dates, sorting, or counting members.
                if (! $profile['birthday_month'] || ! $profile['birthday_day']) {
                    return null;
                }
                $month = $profile['birthday_month'];
                $day = $profile['birthday_day'];
                $celebrate = function (int $year) use ($month, $day): CarbonImmutable {
                    $first = CarbonImmutable::create($year, $month, 1, 0, 0, 0, 'Asia/Jakarta');

                    return $first->day(min($day, $first->daysInMonth));
                };
                $date = $celebrate($today->year);
                if ($date->lessThan($today)) {
                    $date = $celebrate($today->year + 1);
                }
                $days = (int) $today->diffInDays($date);

                // No birth year, age, raw profile, or user email reaches the view.
                return [
                    'id' => $profile['id'], 'name' => $profile['name'],
                    'initials' => $profile['initials'], 'avatar' => $profile['avatar'],
                    'own' => $member->user_id === $viewer->id,
                    'month' => $month, 'day' => $date->day,
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->locale('id')->translatedFormat('j F Y'),
                    'month_label' => $date->locale('id')->translatedFormat('M'),
                    'days' => $days,
                    'countdown' => $days === 0 ? 'Hari ini 🎉' : ($days === 1 ? 'Besok' : $days.' hari lagi'),
                    'leap_adjusted' => $month === 2 && $day === 29 && $date->day === 28,
                ];
            })->filter()->sortBy([['date', 'asc'], ['name', 'asc'], ['id', 'asc']])->values();
    }
}
