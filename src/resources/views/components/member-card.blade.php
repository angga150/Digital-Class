<!-- Member Card Component - Reusable untuk Members -->
<div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition text-center">
    <div class="text-6xl mb-4 flex justify-center">{{ $member['avatar'] ?? '👤' }}</div>
    <h3 class="font-bold text-slate-900 mb-1 line-clamp-1">{{ $member['name'] ?? 'Member' }}</h3>
    <p class="text-xs font-semibold {{ $member['role'] == 'admin' ? 'text-red-600 bg-red-50' : ($member['role'] == 'moderator' ? 'text-orange-600 bg-orange-50' : 'text-indigo-600 bg-indigo-50') }} px-2 py-1 rounded inline-block mb-4">
        {{ ucfirst($member['role'] ?? 'member') }}
    </p>
    <p class="text-sm text-slate-600 mb-2 line-clamp-2">{{ $member['bio'] ?? 'No bio' }}</p>
    <p class="text-sm text-slate-600 mb-4">👥 {{ $member['followers'] ?? 0 }} followers</p>
    <div class="flex gap-2">
        <button class="flex-1 py-2 border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded-lg transition font-semibold text-sm">
            + Follow
        </button>
        <button class="flex-1 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition font-semibold text-sm">
            👤 View
        </button>
    </div>
</div>
