<!-- Birthday Alerts Widget Component -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-400 to-red-500 p-6">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            🎂 Ulang Tahun
        </h3>
    </div>
    <div class="p-6 space-y-3">
        @forelse ($birthdays as $birthday)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">{{ $birthday['avatar'] ?? '🎂' }}</div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $birthday['name'] ?? 'Member' }}</p>
                        <p class="text-xs text-slate-600">
                            @if (($birthday['daysUntil'] ?? 999) == 0)
                                Hari ini! 🎉
                            @elseif (($birthday['daysUntil'] ?? 999) == 1)
                                Besok 🎊
                            @else
                                {{ $birthday['daysUntil'] ?? 0 }} hari lagi
                            @endif
                        </p>
                    </div>
                </div>
                <button class="px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    🎁
                </button>
            </div>
        @empty
            <p class="text-slate-500 text-center text-sm py-2">Tidak ada ulang tahun minggu ini</p>
        @endforelse
    </div>
</div>
