<?php
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $posts = [];
    public $newPost = '';
    public $showCreatePost = false;

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $this->posts = [
            [
                'id' => 1,
                'author' => 'Siti Nurhaliza',
                'avatar' => '👩',
                'role' => 'Admin',
                'type' => 'announcement',
                'content' => '📢 Pengumuman: Deadline pengumpulan project akhir semester diperpanjang hingga 15 Oktober!',
                'timestamp' => 'Hari ini',
                'likes' => 45,
                'comments' => 12,
                'liked' => false
            ],
            [
                'id' => 2,
                'author' => 'Ahmad Wijaya',
                'avatar' => '👨',
                'role' => 'Member',
                'type' => 'discussion',
                'content' => 'Ada yang mau belajar bareng untuk mid-term? Saya mau buat study group di perpus minggu depan 📚',
                'timestamp' => 'Kemarin',
                'likes' => 23,
                'comments' => 8,
                'liked' => false
            ],
            [
                'id' => 3,
                'author' => 'Dewi Santika',
                'avatar' => '👩',
                'role' => 'Member',
                'type' => 'memory',
                'content' => '✨ Seneng banget kemarin gathering-nya! Foto-foto sudah upload di gallery yaa 📸',
                'timestamp' => '2 hari lalu',
                'likes' => 67,
                'comments' => 34,
                'liked' => false
            ],
            [
                'id' => 4,
                'author' => 'Budi Santoso',
                'avatar' => '👨',
                'role' => 'Member',
                'type' => 'question',
                'content' => 'Siapa yang punya catatan kuliah Database dari pertemuan kemarin? 🤔',
                'timestamp' => '3 hari lalu',
                'likes' => 12,
                'comments' => 5,
                'liked' => false
            ],
            [
                'id' => 5,
                'author' => 'Rina Kusuma',
                'avatar' => '👩',
                'role' => 'Member',
                'type' => 'celebration',
                'content' => '🎉 Alhamdulillah diterima di perusahaan impian! Terima kasih teman-teman seperjuangan 💙',
                'timestamp' => '5 hari lalu',
                'likes' => 156,
                'comments' => 78,
                'liked' => false
            ]
        ];
    }

    public function toggleCreatePost()
    {
        $this->showCreatePost = !$this->showCreatePost;
    }

    public function createPost()
    {
        if (empty(trim($this->newPost))) {
            return;
        }

        array_unshift($this->posts, [
            'id' => count($this->posts) + 1,
            'author' => 'You',
            'avatar' => '🧑',
            'role' => 'Member',
            'type' => 'text',
            'content' => $this->newPost,
            'timestamp' => 'Baru saja',
            'likes' => 0,
            'comments' => 0,
            'liked' => false
        ]);

        $this->newPost = '';
        $this->showCreatePost = false;
    }

    public function likePost($postId)
    {
        foreach ($this->posts as &$post) {
            if ($post['id'] == $postId) {
                $post['liked'] = !$post['liked'];
                $post['likes'] += $post['liked'] ? 1 : -1;
            }
        }
    }

    public function deletePost($postId)
    {
        $this->posts = array_filter($this->posts, fn($post) => $post['id'] !== $postId);
    }
}; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            📰 Class Feed
        </h1>
        <p class="text-gray-600 mt-2">Berbagi cerita, pengumuman, dan diskusi kelas di sini! 💬</p>
    </div>

    <!-- Create Post Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        @if(!$showCreatePost)
            <button 
                wire:click="toggleCreatePost"
                class="w-full flex items-center space-x-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors cursor-pointer">
                <span class="text-3xl">🧑</span>
                <span class="text-gray-500 flex-1 text-left">Apa yang ingin kamu bagikan hari ini?</span>
                <span class="text-2xl">✍️</span>
            </button>
        @else
            <div class="space-y-4">
                <textarea 
                    wire:model="newPost"
                    placeholder="Tulis sesuatu untuk kelas..."
                    class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all resize-none"
                    rows="4"></textarea>
                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="toggleCreatePost"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button 
                        wire:click="createPost"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-medium shadow-lg transition-all">
                        Posting 🚀
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Posts Feed -->
    <div class="space-y-4">
        @foreach($posts as $post)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <!-- Post Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-2xl">
                            {{ $post['avatar'] }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $post['author'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $post['role'] }} • {{ $post['timestamp'] }}</p>
                        </div>
                    </div>
                    @if($post['type'] == 'announcement')
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">📢 Pengumuman</span>
                    @elseif($post['type'] == 'celebration')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">🎉 Celebrasi</span>
                    @endif
                </div>

                <!-- Post Content -->
                <p class="text-gray-700 leading-relaxed mb-4">{{ $post['content'] }}</p>

                <!-- Post Actions -->
                <div class="flex items-center space-x-6 pt-4 border-t border-gray-100">
                    <button 
                        wire:click="likePost({{ $post['id'] }})"
                        class="flex items-center space-x-2 {{ $post['liked'] ? 'text-pink-600' : 'text-gray-500' }} hover:text-pink-600 transition-colors">
                        <span class="text-xl">{{ $post['liked'] ? '❤️' : '🤍' }}</span>
                        <span class="font-medium">{{ $post['likes'] }}</span>
                    </button>
                    <button class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                        <span class="text-xl">💬</span>
                        <span class="font-medium">{{ $post['comments'] }}</span>
                    </button>
                    <button class="flex items-center space-x-2 text-gray-500 hover:text-green-600 transition-colors">
                        <span class="text-xl">🔗</span>
                        <span class="font-medium">Share</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
