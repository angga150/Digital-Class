<!-- Event Countdown Widget Component -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
        <h2 class="text-2xl font-bold text-white flex items-center gap-2">
            🎉 Event Countdown
        </h2>
        <p class="text-indigo-100 mt-2">Event menarik yang akan datang</p>
    </div>

    <div class="p-6 space-y-4">
        @forelse ($events as $event)
            <div class="border-l-4 border-indigo-600 pl-4 py-3">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $event['title'] ?? 'Event' }}</h3>
                        <p class="text-sm text-slate-600">📍 {{ $event['location'] ?? 'TBA' }}</p>
                    </div>
                    <span class="text-2xl">
                        @if (($event['daysUntil'] ?? 999) <= 1)
                            ⏰
                        @elseif (($event['daysUntil'] ?? 999) <= 3)
                            🔥
                        @else
                            📅
                        @endif
                    </span>
                </div>
                <div class="flex gap-4 text-sm text-slate-600">
                    <span>📅 {{ $event['date']->format('d M Y') ?? 'TBA' }}</span>
                    <span>👥 {{ $event['attendees'] ?? 0 }} orang</span>
                </div>
            </div>
        @empty
            <p class="text-slate-500 text-center py-4">Tidak ada event mendatang</p>
        @endforelse
    </div>
</div>
