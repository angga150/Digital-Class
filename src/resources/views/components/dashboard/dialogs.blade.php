<dialog id="dashboard-dialog" x-ref="dialog" @click="if ($event.target === $refs.dialog) closeModal()" @close="modal = ''" aria-labelledby="dialog-title" class="max-h-[85dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto rounded-3xl border border-line bg-white p-0 text-ink shadow-2xl backdrop:bg-black/30">
    <div class="flex items-start justify-between gap-3 border-b border-line p-5 sm:p-6">
        <div><p class="mb-1.5 text-[10px] font-bold uppercase tracking-widest text-orange-dark">Digital Class</p><h2 id="dialog-title" x-ref="dialogTitle" tabindex="-1" class="modal-heading" x-text="modalTitle"></h2></div>
        <button @click="closeModal()" class="icon-button -mr-2 -mt-1" aria-label="Tutup dialog"><x-icon name="close" /></button>
    </div>
    <div class="p-5 sm:p-6">
        <section x-show="modal === 'memory'">
            <img src="{{ asset('img/dashboard/campus-friends.png') }}" width="1774" height="887" alt="Foto ilustrasi AI, lima mahasiswa berbincang bersama di taman kampus" class="w-full rounded-2xl">
            <p class="mt-4 text-sm leading-6">Obrolan setelah kelas yang selalu kepanjangan. Hal kecil begini yang nanti paling kita kangenin.</p>
            <p class="mt-4 rounded-xl bg-paper p-3 text-xs leading-5 text-muted">Album contoh · 1 foto. Gambar dibuat dengan AI sebagai ilustrasi, bukan foto anggota kelas sungguhan.</p>
        </section>
        <section x-show="modal === 'settings'" class="space-y-4 text-sm leading-7">
            <p>Ini pratinjau dashboard <strong>Digital Class</strong>, dibuat dengan Laravel, Livewire, dan Tailwind CSS.</p>
            <div class="rounded-2xl bg-[#fff7e9] p-4"><p class="font-semibold">Coba dengan bebas</p><p class="mt-1 text-muted">Galeri masih berupa pratinjau.</p></div>
            <p class="text-muted">Akun, profil, cerita, komentar, suka, agenda, konfirmasi kehadiran, suara polling, pengumuman, dan notifikasi sudah tersimpan. Simpan cerita favoritmu atau cari data kelas lewat kolom pencarian. Kalender ulang tahun mengikuti data serta privasi profil. Buka Profilku untuk mengubah foto, cerita pribadi, dan privasi. Cerita kelas hanya terlihat oleh anggota kelas yang sama.</p>
        </section>
    </div>
</dialog>
