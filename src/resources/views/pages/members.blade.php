<?php
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $members = [];
    public $selectedRole = 'all';
    public $searchQuery = '';
    public $followedMembers = [];

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $this->members = [
            ['id' => 1, 'name' => 'Siti Nurhaliza', 'role' => 'admin', 'avatar' => '👩', 'followers' => 342, 'bio' => 'Admin kelas TI-6A'],
            ['id' => 2, 'name' => 'Ahmad Wijaya', 'role' => 'moderator', 'avatar' => '👨', 'followers' => 256, 'bio' => 'Moderator & passionate developer'],
            ['id' => 3, 'name' => 'Dewi Santika', 'role' => 'member', 'avatar' => '👩', 'followers' => 189, 'bio' => 'UI/UX Designer'],
            ['id' => 4, 'name' => 'Budi Santoso', 'role' => 'member', 'avatar' => '👨', 'followers' => 124, 'bio' => 'Backend Developer'],
            ['id' => 5, 'name' => 'Rina Kusuma', 'role' => 'member', 'avatar' => '👩', 'followers' => 98, 'bio' => 'Full Stack Developer'],
            ['id' => 6, 'name' => 'Rafi Rahman', 'role' => 'member', 'avatar' => '👨', 'followers' => 76, 'bio' => 'Mobile Developer'],
            ['id' => 7, 'name' => 'Sinta Wijaya', 'role' => 'member', 'avatar' => '👩', 'followers' => 54, 'bio' => 'Data Analyst'],
            ['id' => 8, 'name' => 'Doni Hendra', 'role' => 'member', 'avatar' => '👨', 'followers' => 32, 'bio' => 'DevOps Engineer'],
        ];
    }

    public function filterByRole($role)
    {
        $this->selectedRole = $role;
    }

    public function toggleFollow($memberId)
    {
        if (in_array($memberId, $this->followedMembers)) {
            $this->followedMembers = array_filter($this->followedMembers, fn($id) => $id !== $memberId);
        } else {
            $this->followedMembers[] = $memberId;
        }
    }

    public function getFilteredMembers()
    {
        return array_filter($this->members, function ($member) {
            if ($this->selectedRole !== 'all' && $member['role'] !== $this->selectedRole) {
                return false;
            }
            if (!empty($this->searchQuery) && stripos($member['name'], $this->searchQuery) === false) {
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
            👥 Class Members
        </h1>
        <p class="text-gray-600 mt-2">Kenalan dan connect dengan teman-teman sekelas! 🤝</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Role Filter -->
            <div class="flex flex-wrap gap-2">
                <button 
                    wire:click="filterByRole('all')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedRole === 'all' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </button>
                <button 
                    wire:click="filterByRole('admin')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedRole === 'admin' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    👑 Admin
                </button>
                <button 
                    wire:click="filterByRole('moderator')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedRole === 'moderator' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    ⭐ Moderator
                </button>
                <button 
                    wire:click="filterByRole('member')"
                    class="px-4 py-2 rounded-xl font-medium transition-all {{ $selectedRole === 'member' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    👤 Member
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="searchQuery"
                    placeholder="Cari member..." 
                    class="pl-10 pr-4 py-2 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all w-full md:w-64">
                <span class="absolute left-3 top-2.5 text-xl">🔍</span>
            </div>
        </div>
    </div>

    <!-- Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($this->getFilteredMembers() as $member)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all">
                <!-- Avatar & Role Badge -->
                <div class="flex items-start justify-between mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-3xl">
                        {{ $member['avatar'] }}
                    </div>
                    @if($member['role'] === 'admin')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">👑 Admin</span>
                    @elseif($member['role'] === 'moderator')
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">⭐ Mod</span>
                    @endif
                </div>

                <!-- Member Info -->
                <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $member['name'] }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ $member['bio'] }}</p>

                <!-- Stats -->
                <div class="flex items-center space-x-4 mb-4 text-sm text-gray-600">
                    <span>👥 {{ $member['followers'] }} followers</span>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button 
                        wire:click="toggleFollow({{ $member['id'] }})"
                        class="flex-1 py-2 rounded-xl font-medium transition-all {{ in_array($member['id'], $followedMembers) ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700' }}">
                        {{ in_array($member['id'], $followedMembers) ? 'Following' : 'Follow' }}
                    </button>
                    <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        💬
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @if(count($this->getFilteredMembers()) === 0)
        <div class="text-center py-16">
            <span class="text-6xl mb-4 block">😔</span>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak ada member ditemukan</h3>
            <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian</p>
        </div>
    @endif
</div>
