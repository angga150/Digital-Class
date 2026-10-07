<?php
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $user = [];
    public $isEditing = false;
    public $editData = [];
    public $stats = [];
    public $achievements = [];
    public $userPosts = [];

    public function mount()
    {
        $this->loadProfileData();
    }

    public function loadProfileData()
    {
        $this->user = [
            'id' => 1,
            'name' => 'Raditya Pratama',
            'username' => '@radityapratama',
            'bio' => 'Passionate about web development, UI/UX design, dan community building. Coffee addict ☕ | Always learning 📚',
            'email' => 'raditya.pratama@student.edu',
            'location' => 'Jakarta, Indonesia',
            'joinedDate' => 'Januari 2025',
            'website' => 'raditya.dev',
            'avatar' => '👨',
            'coverColor' => 'from-indigo-600 via-purple-600 to-pink-600'
        ];

        $this->editData = $this->user;

        $this->stats = [
            ['label' => 'Followers', 'value' => 342],
            ['label' => 'Following', 'value' => 128],
            ['label' => 'Posts', 'value' => 45],
            ['label' => 'Events', 'value' => 156]
        ];

        $this->achievements = [
            ['id' => 1, 'icon' => '🥇', 'title' => 'Best Project 2026', 'subtitle' => 'komunitas digital'],
            ['id' => 2, 'icon' => '⭐', 'title' => 'Aktif Member', 'subtitle' => '3 bulan berturut-turut'],
            ['id' => 3, 'icon' => '💬', 'title' => 'Helpful Community', 'subtitle' => '50+ helpful comments'],
            ['id' => 4, 'icon' => '📸', 'title' => 'Gallery Master', 'subtitle' => '100+ photos uploaded']
        ];

        $this->userPosts = [
            [
                'id' => 1,
                'content' => 'Just launched my new portfolio website! Check it out and give me feedback 🚀',
                'timestamp' => '2 hari lalu',
                'likes' => 45,
                'comments' => 12
            ],
            [
                'id' => 2,
                'content' => 'Web development tips: Always prioritize accessibility from the start, not as an afterthought.',
                'timestamp' => '5 hari lalu',
                'likes' => 89,
                'comments' => 28
            ]
        ];
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function saveProfile()
    {
        $this->user = $this->editData;
        $this->isEditing = false;
    }
}; ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Cover & Profile Header -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
        <!-- Cover Image -->
        <div class="h-48 bg-gradient-to-r {{ $user['coverColor'] }}"></div>

        <!-- Profile Info -->
        <div class="px-6 pb-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 mb-4">
                <!-- Avatar -->
                <div class="flex items-end space-x-4">
                    <div class="w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-600 border-4 border-white rounded-full flex items-center justify-center text-6xl shadow-xl">
                        {{ $user['avatar'] }}
                    </div>
                    <div class="pb-2">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $user['name'] }}</h1>
                        <p class="text-gray-500">{{ $user['username'] }}</p>
                    </div>
                </div>

                <!-- Edit Button -->
                @if(!$isEditing)
                    <button 
                        wire:click="toggleEdit"
                        class="mt-4 md:mt-0 px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-medium shadow-lg transition-all">
                        ✏️ Edit Profile
                    </button>
                @endif
            </div>

            @if($isEditing)
                <!-- Edit Form -->
                <div class="space-y-4 mt-6 p-6 bg-gray-50 rounded-xl">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" wire:model="editData.name" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea wire:model="editData.bio" rows="3" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" wire:model="editData.location" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="text" wire:model="editData.website" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button 
                            wire:click="toggleEdit"
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">
                            Batal
                        </button>
                        <button 
                            wire:click="saveProfile"
                            class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-medium shadow-lg transition-all">
                            Simpan
                        </button>
                    </div>
                </div>
            @else
                <!-- Profile Details -->
                <div class="space-y-3 mt-6">
                    <p class="text-gray-700 leading-relaxed">{{ $user['bio'] }}</p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <span>📍 {{ $user['location'] }}</span>
                        <span>🌐 <a href="https://{{ $user['website'] }}" class="text-indigo-600 hover:underline">{{ $user['website'] }}</a></span>
                        <span>📅 Joined {{ $user['joinedDate'] }}</span>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                @foreach($stats as $stat)
                    <div class="text-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Achievements & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Achievements -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🏆 Achievements</h2>
                <div class="space-y-3">
                    @foreach($achievements as $achievement)
                        <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl hover:shadow-md transition-shadow">
                            <span class="text-3xl">{{ $achievement['icon'] }}</span>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $achievement['title'] }}</h4>
                                <p class="text-xs text-gray-600">{{ $achievement['subtitle'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📝 Recent Activity</h2>
                <div class="space-y-4">
                    @foreach($userPosts as $post)
                        <div class="p-4 border-2 border-gray-100 rounded-xl hover:border-indigo-200 hover:shadow-md transition-all">
                            <p class="text-gray-700 mb-3">{{ $post['content'] }}</p>
                            <div class="flex items-center space-x-6 text-sm text-gray-600">
                                <span>❤️ {{ $post['likes'] }}</span>
                                <span>💬 {{ $post['comments'] }}</span>
                                <span class="ml-auto">{{ $post['timestamp'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
