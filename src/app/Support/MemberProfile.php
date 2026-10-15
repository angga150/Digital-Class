<?php

namespace App\Support;

use App\Models\ClassMember;
use App\Models\User;

class MemberProfile
{
    // Return only display-safe values; never serialize the raw profile into Livewire state.
    public static function present(ClassMember $member, User $viewer): array
    {
        $profile = $member->user->profile;
        $owner = $member->user_id === $viewer->id;
        $viewerMembership = CurrentClass::membership($viewer);
        abort_unless($viewerMembership?->class_id === $member->class_id, 404);
        $admin = $viewerMembership->role === 'admin';
        $canSee = fn (?string $visibility) => $owner || $visibility === 'class_only' || ($visibility === 'admin_only' && $admin);
        $visible = $canSee($profile?->profile_visibility ?? 'class_only');
        $name = ($visible ? $profile?->nickname : null) ?: $member->user->name;
        $birthday = $profile?->birth_date;
        $birthdayVisible = $visible && $canSee($profile?->birthday_visibility ?? 'class_only');

        return [
            'id' => $member->id,
            'name' => $name,
            'initials' => mb_strtoupper(mb_substr($name, 0, 1)),
            'role' => $member->role === 'admin' ? 'Pengurus kelas' : 'Anggota kelas',
            'joined' => $member->joined_at->locale('id')->translatedFormat('F Y'),
            'visible' => $visible,
            'avatar' => $visible && $profile?->avatar_path ? route('members.avatar', $member) : null,
            'bio' => $visible ? $profile?->bio : null,
            'hobby' => $visible ? ($profile?->hobby ?? []) : [],
            'favorite' => $visible ? ($profile?->favorite ?? []) : [],
            'fun_fact' => $visible ? $profile?->fun_fact : null,
            'birthday' => $birthdayVisible && $birthday ? $birthday->locale('id')->translatedFormat($profile->birth_year_visible ? 'j F Y' : 'j F') : null,
        ];
    }
}
