<!-- Event Card Component - Reusable untuk Events -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition">
    <div class="md:flex">
        <!-- Date Card -->
        <div class="md:w-32 bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-6 flex flex-col justify-center">
            @php
                // Handle berbagai format date
                $date = $event['date'] ?? null;
                
                // Convert ke Carbon jika string format tanggal
                if (is_string($date)) {
                    try {
                        $date = \Carbon\Carbon::parse($date);
                    } catch (\Exception $e) {
                        // Jika gagal parse, set null dan gunakan fallback
                        $date = null;
                    }
                }
                
                // Jika sudah Carbon object, format-nya
                if ($date instanceof \Carbon\Carbon) {
                    $day = $date->format('d');
                    $month = $date->format('M');
                    $year = $date->format('Y');
                } else {
                    // Fallback jika parsing gagal
                    $day = '00';
                    $month = 'JAN';
                    $year = '2026';
                }
            @endphp
            
            <div class="text-4xl font-bold">{{ $day }}</div>
            <div class="text-sm font-semibold">{{ $month }}</div>
            <div class="text-xs mt-2">{{ $year }}</div>
        </div>

        <!-- Event Info -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $event['title'] ?? 'Event' }}</h3>
                    <p class="text-sm text-indigo-600 font-semibold capitalize">{{ $event['category'] ?? 'general' }}</p>
                </div>
            </div>

            <p class="text-slate-600 mb-4 line-clamp-2">{{ $event['description'] ?? '' }}</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                <div>
                    <p class="text-xs text-slate-500 font-semibold">WAKTU</p>
                    <p class="font-semibold text-slate-900">{{ $event['time'] ?? '--:--' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">LOKASI</p>
                    <p class="font-semibold text-slate-900">{{ $event['location'] ?? 'TBA' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">PENYELENGGARA</p>
                    <p class="font-semibold text-slate-900">{{ $event['organizer'] ?? 'Admin' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">PESERTA</p>
                    <p class="font-semibold text-slate-900">{{ $event['attendees'] ?? 0 }} orang</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold text-sm">
                    ✅ Daftar
                </button>
                <button class="px-6 py-2 border-2 border-slate-300 text-slate-600 rounded-lg hover:border-indigo-600 hover:text-indigo-600 transition font-semibold text-sm">
                    📄 Detail
                </button>
            </div>
        </div>
    </div>
</div>
