<!-- Post Card Component - Reusable untuk Feed -->
<div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition">
    <!-- Post Header -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="text-4xl">{{ $post['avatar'] ?? '👤' }}</div>
            <div>
                <p class="font-bold text-slate-900">{{ $post['author'] ?? 'User' }}</p>
                <div class="flex items-center gap-2 text-xs text-slate-600">
                    <span>{{ $post['role'] ?? 'Member' }}</span>
                    <span>•</span>
                    <span>{{ $post['timestamp'] ?? 'now' }}</span>
                </div>
            </div>
        </div>
        <div class="text-2xl">
            @switch($post['type'] ?? 'post')
                @case('announcement')
                    📢
                    @break
                @case('discussion')
                    💬
                    @break
                @case('memory')
                    ✨
                    @break
                @case('question')
                    🤔
                    @break
                @case('celebration')
                    🎉
                    @break
                @default
                    📝
            @endswitch
        </div>
    </div>

    <!-- Post Content -->
    <p class="text-slate-800 mb-6 leading-relaxed">{{ $post['content'] ?? '' }}</p>

    <!-- Post Actions -->
    <div class="flex gap-6 text-slate-600 text-sm border-t pt-4">
        <button class="flex items-center gap-2 {{ ($post['liked'] ?? false) ? 'text-red-600' : '' }} hover:text-red-600 transition flex-1">
            <span>{{ ($post['liked'] ?? false) ? '❤️' : '🤍' }}</span>
            {{ $post['likes'] ?? 0 }}
        </button>
        <button class="flex items-center gap-2 hover:text-indigo-600 transition flex-1">
            <span>💬</span>
            {{ $post['comments'] ?? 0 }}
        </button>
        <button class="flex items-center gap-2 hover:text-red-600 transition flex-1">
            <span>🗑️</span>
            Hapus
        </button>
    </div>
</div>
