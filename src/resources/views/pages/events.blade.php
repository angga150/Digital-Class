<?php
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Carbon;

new class extends Component {
    public $events = [];
    public $selectedCategory = 'all';
    public $searchQuery = '';

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = [
            [
                'id' => 1,
                'title' => 'Workshop React.js Advanced',
                'category' => 'workshop',
                'date' => Carbon::now()->addDays(3),
                'time' => '14:00',
                'location' => 'Lab Komputer Lantai 3',
                'organizer' => 'Siti Nurhaliza',
                'attendees' => 24,
                'description' => 'Pelajari teknik-teknik advanced React.js dengan praktik langsung.'
            ],
            [
                'id' => 2,
                'title' => 'Gathering BBQ Kelas',
                'category' => 'gathering',
                'date' => Carbon::now()->addDays(7),
                'time' => '16:00',
                'location' => 'Lapangan Universitas',
                'organizer' => 'Ahmad Wijaya',
                'attendees' => 45,
                'description' => 'Gathering santai kelas TI-6A, makan bareng sambil berbagi cerita.'
            ],
            [
                'id' => 3,
                'title' => 'Seminar Cloud Computing',
                'category' => 'seminar',
                'date' => Carbon::now()->addDays(14),
                'time' => '10:00',
                'location' => 'Aula Utama',
                'organizer' => 'Dewi Santika',
                'attendees' => 89,
                'description' => 'Seminar tentang trend terkini cloud computing di industri.'
            ],
            [
                'id' => 4,
                'title' => 'Futsal Championship Round 1',
                'category' => 'olahraga',
                'date' => Carbon::now()->addDays(10),
                'time' => '19:00',
                'location' => 'Futsal Court',
                'organizer' => 'Budi Santoso',
                'attendees' => 30,
                'description' => 'Pertandingan futsal antar angkatan.'
            ],
            [
                'id' => 5,
                'title' => 'Workshop UI/UX Design',
                'category' => 'workshop',
                'date' => Carbon::now()->addDays(21),
                'time' => '13:00',
                'location' => 'Design Studio',
                'organizer' => 'Rina Kusuma',
                'attendees' => 18,
                'description' => 'Belajar fundamental design UI/UX modern dengan Figma.'
            ]
        ];
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function getFilteredEvents()
    {
        return array_filter($this->events, function ($event) {
            if ($this->selectedCategory !== 'all' && $event['category'] !== $this->selectedCategory) {
                return false;
            }
            if (!empty($this->searchQuery) && stripos($event['title'], $this->searchQuery) === false) {
                return false;
            }
            return true;
        });
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            📅 Class Events
        </h1>
        <p class="text-gray-600 mt-2">Temukan dan ikuti event menarik yang diadakan kelas! 🎉</p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <button 
                    wire:click="filterByCategory('all')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedCategory === 'all' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </button>
                <button 
                    wire:click="filterByCategory('workshop')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedCategory === 'workshop' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    🎓 Workshop
                </button>
                <button 
                    wire:click="filterByCategory('seminar')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedCategory === 'seminar' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    💼 Seminar
                </button>
                <button 
                    wire:click="filterByCategory('gathering')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedCategory === 'gathering' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    🎊 Gathering
                </button>
                <button 
                    wire:click="filterByCategory('olahraga')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedCategory === 'olahraga' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    ⚽ Olahraga
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="searchQuery"
                    placeholder="Cari event..." 
                    class="pl-10 pr-4 py-2 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all w-full md:w-64">
                <span class="absolute left-3 top-2.5 text-xl">🔍</span>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($this->getFilteredEvents() as $event)
            <x-event-card :event="$event" detailed />
        @endforeach
    </div>

    @if(count($this->getFilteredEvents()) === 0)
        <div class="text-center py-16">
            <span class="text-6xl mb-4 block">😔</span>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak ada event ditemukan</h3>
            <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian</p>
        </div>
    @endif
</div>
