<!-- Poll Card Component - Reusable untuk Polling -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            📊 {{ $poll['title'] ?? 'Poll' }}
        </h3>
    </div>
    <div class="p-6 space-y-4">
        <p class="font-semibold text-slate-900">{{ $poll['question'] ?? 'What do you think?' }}</p>
        <div class="space-y-3">
            @foreach ($poll['options'] as $index => $option)
                <button class="w-full text-left p-3 border-2 border-slate-200 rounded-lg hover:border-indigo-600 transition">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-medium text-slate-900">{{ $option['label'] ?? 'Option' }}</span>
                        <span class="text-sm text-slate-600">{{ $option['percentage'] ?? '0' }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $option['percentage'] ?? 0 }}%"></div>
                    </div>
                </button>
            @endforeach
        </div>
        <p class="text-xs text-slate-500 text-center">{{ $poll['totalVotes'] ?? 0 }} suara</p>
    </div>
</div>
