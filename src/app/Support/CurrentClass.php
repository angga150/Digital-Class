<?php

namespace App\Support;

use App\Models\ClassMember;
use App\Models\User;

class CurrentClass
{
    // Explicitly bypass the class scope only while resolving the signed-in user's own membership.
    public static function membership(?User $user = null): ?ClassMember
    {
        $user ??= auth()->user();

        return $user?->classMemberships()->withoutGlobalScope('current_class')
            ->whereHas('classModel', fn ($query) => $query->where('is_active', true))
            ->with('classModel')->orderBy('id')->first();
    }
}
