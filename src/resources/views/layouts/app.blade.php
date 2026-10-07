<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Class Universe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50">
    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white shadow-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 hover:opacity-80 transition">
                    <span class="text-3xl">🎓</span>
                    <div class="hidden sm:block">
                        <h1 class="font-bold text-slate-900 text-lg">Digital Class</h1>
                        <p class="text-xs text-slate-500">TI-6A Community</p>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="/" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                        📊 Dashboard
                    </a>
                    <a href="/feed" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('feed') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                        📰 Feed
                    </a>
                    <a href="/events" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('events') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                        🎉 Events
                    </a>
                    <a href="/gallery" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('gallery') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                        📸 Gallery
                    </a>
                    <a href="/members" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('members') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                        👥 Members
                    </a>
                </div>

                <!-- User Profile Button -->
                <a href="/profile" class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-lg hover:shadow-lg transition font-bold text-white">
                    👤
                </a>
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden flex gap-2 pb-3 overflow-x-auto scrollbar-hide">
                <a href="/" class="px-3 py-1 text-xs bg-slate-100 text-slate-600 rounded-full whitespace-nowrap hover:bg-slate-200 transition">📊 Dashboard</a>
                <a href="/feed" class="px-3 py-1 text-xs bg-slate-100 text-slate-600 rounded-full whitespace-nowrap hover:bg-slate-200 transition">📰 Feed</a>
                <a href="/events" class="px-3 py-1 text-xs bg-slate-100 text-slate-600 rounded-full whitespace-nowrap hover:bg-slate-200 transition">🎉 Events</a>
                <a href="/gallery" class="px-3 py-1 text-xs bg-slate-100 text-slate-600 rounded-full whitespace-nowrap hover:bg-slate-200 transition">📸 Gallery</a>
                <a href="/members" class="px-3 py-1 text-xs bg-slate-100 text-slate-600 rounded-full whitespace-nowrap hover:bg-slate-200 transition">👥 Members</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-64px-200px)]">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white mt-20 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-bold mb-3 flex items-center gap-2 text-lg">🎓 Digital Class Universe</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Ruang digital untuk komunitas kelas TI-6A. Tempat berbagi, belajar, dan membuat kenangan bersama.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-sm">Navigasi Utama</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/" class="hover:text-white transition">📊 Dashboard</a></li>
                        <li><a href="/feed" class="hover:text-white transition">📰 Social Feed</a></li>
                        <li><a href="/events" class="hover:text-white transition">🎉 Events</a></li>
                        <li><a href="/gallery" class="hover:text-white transition">📸 Gallery</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-sm">Konten</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/members" class="hover:text-white transition">👥 Members</a></li>
                        <li><a href="/profile" class="hover:text-white transition">👤 Profile</a></li>
                        <li><a href="/health" class="hover:text-white transition">🏥 Health</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-sm">Kontak & Sosial</h4>
                    <p class="text-slate-400 text-sm mb-3">📧 admin@digitalclass.edu</p>
                    <p class="text-slate-400 text-sm mb-4">📱 +62 812 3456 7890</p>
                    <div class="flex gap-3">
                        <a href="#" class="text-xl hover:opacity-70 transition">📘</a>
                        <a href="#" class="text-xl hover:opacity-70 transition">🐦</a>
                        <a href="#" class="text-xl hover:opacity-70 transition">📷</a>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-slate-400 text-sm">
                    <p>© 2026 Digital Class Universe. All rights reserved.</p>
                    <div class="flex gap-6 mt-4 md:mt-0">
                        <a href="#" class="hover:text-white transition">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition">Terms of Service</a>
                        <a href="#" class="hover:text-white transition">Feedback</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
