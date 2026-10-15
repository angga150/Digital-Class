<dialog id="dashboard-dialog" x-ref="dialog" @click="if ($event.target === $refs.dialog) closeModal()" @close="modal = ''" aria-labelledby="dialog-title" class="max-h-[85dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto rounded-3xl border border-line bg-white p-0 text-ink shadow-2xl backdrop:bg-black/30">
    <div class="flex items-start justify-between gap-3 border-b border-line p-5 sm:p-6">
        <div><p class="mb-1.5 text-[10px] font-bold uppercase tracking-widest text-orange-dark">Digital Class · mode demo</p><h2 id="dialog-title" x-ref="dialogTitle" tabindex="-1" class="modal-heading" x-text="modalTitle"></h2></div>
        <button @click="closeModal()" class="icon-button -mr-2 -mt-1" aria-label="Tutup dialog"><x-icon name="close" /></button>
    </div>
    <div class="p-5 sm:p-6">
        <section x-show="modal === 'announcement'" class="space-y-4">
            <span class="inline-flex rounded-full bg-sage px-3 py-1 text-xs font-semibold text-[#526443]">Pengumuman contoh</span>
            <h3 class="text-xl font-bold">Ruang kuliah besok pindah ke B.204</h3>
            <p class="text-sm leading-7 text-muted">Untuk pertemuan besok, kelas kita pindah ke gedung B, lantai 2, ruang 204. Jam masuk tetap sesuai jadwal. Sampai ketemu di kelas!</p>
            <p class="border-t border-line pt-4 text-xs text-muted">Pengurus kelas · data contoh, bukan pengumuman resmi</p>
        </section>
        <section x-show="modal === 'birthdays'">
            <p class="mb-5 text-sm leading-6 text-muted">Sedikit perhatian, banyak senyuman. Ini kalender ulang tahun dengan data contoh.</p>
            <div class="flex items-center gap-4 rounded-2xl bg-[#fff3c8] p-4"><x-avatar initials="R" tone="sage" /><div class="flex-1"><p class="font-semibold">Rani</p><p class="mt-1 text-sm text-muted">{{ now('Asia/Jakarta')->addDays(2)->locale('id')->translatedFormat('j F') }}</p></div><span class="text-xs font-semibold text-orange-dark">2 hari lagi</span><x-icon name="cake" class="text-orange" :size="28" /></div>
            <p class="mt-5 text-xs leading-6 text-muted">Pada versi lengkap, hanya ulang tahun yang diizinkan pemiliknya yang ditampilkan. Tahun lahir tidak ditampilkan.</p>
        </section>
        <section x-show="modal === 'memory'">
            <img src="{{ asset('img/dashboard/campus-friends.png') }}" width="1774" height="887" alt="Foto ilustrasi AI, lima mahasiswa berbincang bersama di taman kampus" class="w-full rounded-2xl">
            <p class="mt-4 text-sm leading-6">Obrolan setelah kelas yang selalu kepanjangan. Hal kecil begini yang nanti paling kita kangenin.</p>
            <p class="mt-4 rounded-xl bg-paper p-3 text-xs leading-5 text-muted">Album contoh · 1 foto. Gambar dibuat dengan AI sebagai ilustrasi, bukan foto anggota kelas sungguhan.</p>
        </section>
        <section x-show="modal === 'notifications'">
            <p class="mb-4 text-xs text-muted">Pemberitahuan contoh untuk pratinjau tampilan.</p>
            <button @click="openModal('announcement')" class="flex w-full items-start gap-3 rounded-2xl bg-sage p-4 text-left"><x-icon name="megaphone" class="mt-1 text-[#526443]" /><span><span class="block text-sm font-semibold">Ada perubahan ruang kelas</span><span class="mt-1 block text-xs text-muted">Baca info ruang B.204</span></span></button>
            <button @click="openModal('birthdays')" class="mt-3 flex w-full items-start gap-3 rounded-2xl bg-[#fff3c8] p-4 text-left"><x-icon name="cake" class="mt-1 text-orange-dark" /><span><span class="block text-sm font-semibold">Rani ulang tahun 2 hari lagi</span><span class="mt-1 block text-xs text-muted">Jangan lupa kasih ucapan, ya!</span></span></button>
        </section>
        <section x-show="modal === 'settings'" class="space-y-4 text-sm leading-7">
            <p>Ini pratinjau dashboard <strong>Digital Class</strong>, dibuat dengan Laravel, Livewire, dan Tailwind CSS.</p>
            <div class="rounded-2xl bg-[#fff7e9] p-4"><p class="font-semibold">Coba dengan bebas</p><p class="mt-1 text-muted">Ulang tahun, notifikasi, galeri contoh, dan polling masih berupa pratinjau. Pilihan polling kembali ke awal saat halaman dimuat ulang.</p></div>
            <p class="text-muted">Akun, profil, cerita, komentar, suka, agenda, dan konfirmasi kehadiran sudah tersimpan. Buka Profilku untuk mengubah foto, cerita pribadi, dan privasi. Cerita kelas hanya terlihat oleh anggota kelas yang sama.</p>
        </section>
    </div>
</dialog>
