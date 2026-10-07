<?php
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $albums = [];
    public $selectedAlbum = null;
    public $photos = [];

    public function mount()
    {
        $this->loadAlbums();
    }

    public function loadAlbums()
    {
        $this->albums = [
            ['id' => 'all', 'name' => 'Semua', 'count' => 48],
            ['id' => 'event', 'name' => 'Event 2026', 'count' => 24],
            ['id' => 'workshop', 'name' => 'Workshop', 'count' => 12],
            ['id' => 'gathering', 'name' => 'Gathering', 'count' => 8],
            ['id' => 'olahraga', 'name' => 'Olahraga', 'count' => 4]
        ];

        $this->selectedAlbum = 'all';
        $this->loadPhotos();
    }

    public function loadPhotos()
    {
        $photos = [
            ['title' => 'Workshop React', 'date' => '15 Sept', 'image' => 'img/gallery/coding-workshop.png', 'alt' => 'Ilustrasi AI mahasiswa berkolaborasi dalam workshop coding'],
            ['title' => 'Gathering BBQ', 'date' => '10 Sept'],
            ['title' => 'Talk Show', 'date' => '8 Sept'],
            ['title' => 'Futsal Match', 'date' => '5 Sept'],
            ['title' => 'Community Photo', 'date' => '1 Sept'],
            ['title' => 'Event Launching', 'date' => '28 Aug'],
            ['title' => 'Workshop Design', 'date' => '25 Aug'],
            ['title' => 'Member Gathering', 'date' => '20 Aug'],
        ];

        $this->photos = array_merge($photos, $photos);
    }

    public function selectAlbum($albumId)
    {
        $this->selectedAlbum = $albumId;
        $this->loadPhotos();
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            📸 Class Gallery
        </h1>
        <p class="text-gray-600 mt-2">Kenangan indah kita bersama dalam foto-foto keren! ✨</p>
    </div>

    <!-- Album Tabs -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-wrap gap-3">
            @foreach($albums as $album)
                <button 
                    wire:click="selectAlbum('{{ $album['id'] }}')"
                    class="px-6 py-3 rounded-xl font-medium transition-all {{ $selectedAlbum === $album['id'] ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $album['name'] }}
                    <span class="ml-2 text-sm opacity-75">({{ $album['count'] }})</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Photo Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($photos as $index => $photo)
            <div class="group relative aspect-square rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all cursor-pointer">
                <!-- Placeholder gradient background -->
                <div class="absolute inset-0 bg-gradient-to-br {{ $index % 5 === 0 ? 'from-pink-400 to-purple-600' : ($index % 5 === 1 ? 'from-blue-400 to-indigo-600' : ($index % 5 === 2 ? 'from-green-400 to-emerald-600' : ($index % 5 === 3 ? 'from-yellow-400 to-orange-600' : 'from-purple-400 to-pink-600'))) }}"></div>
                
                @if(isset($photo['image']))
                    <img src="{{ asset($photo['image']) }}" alt="{{ $photo['alt'] }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" width="1536" height="1024">
                    <span class="absolute top-3 left-3 rounded-full bg-white/90 px-2 py-1 text-xs font-medium text-gray-700">Ilustrasi AI</span>
                @endif

                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                    <div class="text-white">
                        <h3 class="font-bold text-lg">{{ $photo['title'] }}</h3>
                        <p class="text-sm text-gray-200">{{ $photo['date'] }}</p>
                    </div>
                </div>

                <!-- Icon overlay -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-6xl">📷</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Upload Button -->
    <div class="fixed bottom-8 right-8">
        <button class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full shadow-2xl hover:scale-110 transition-all flex items-center justify-center text-3xl">
            ➕
        </button>
    </div>
</div>
