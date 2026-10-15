<?php

namespace App\Http\Controllers;

use App\Models\ClassMember;
use App\Support\MemberProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function show(Request $request, ClassMember $member)
    {
        return view('members.show', ['member' => MemberProfile::present($member->load('user.profile'), $request->user())]);
    }

    public function avatar(Request $request, ClassMember $member)
    {
        $card = MemberProfile::present($member->load('user.profile'), $request->user());
        abort_unless($card['avatar'], 404);
        $path = $member->user->profile->avatar_path;
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, null, ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
