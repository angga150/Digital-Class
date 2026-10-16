<dialog id="dashboard-dialog" x-ref="dialog" @click="if ($event.target === $refs.dialog) closeModal()" @close="modal = ''" aria-labelledby="dialog-title" class="max-h-[85dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto rounded-3xl border border-line bg-white p-0 text-ink shadow-2xl backdrop:bg-black/30">
    <div class="flex items-start justify-between gap-3 border-b border-line p-5 sm:p-6">
        <div><p class="mb-1.5 text-[10px] font-bold uppercase tracking-widest text-orange-dark">Digital Class · mode demo</p><h2 id="dialog-title" x-ref="dialogTitle" tabindex="-1" class="modal-heading" x-text="modalTitle"></h2></div>
        <button @click="closeModal()" class="icon-button -mr-2 -mt-1" aria-label="Tutup dialog"><x-icon name="close" /></button>
    </div>
    <div class="p-5 sm:p-6">
        <section x-show="modal === 'memory'">
            <img src="{{ asset('img/dashboard/campus-friends.png') }}" width="1774" height="887" alt="Foto ilustrasi AI, lima mahasiswa berbincang bersama di taman kampus" class="w-full rounded-2xl">
            <p class="mt-4 text-sm leading-6">Obrolan setelah kelas yang selalu kepanjangan. Hal kecil begini yang nanti paling kita kangenin.</p>
            <p class="mt-4 rounded-xl bg-paper p-3 text-xs leading-5 text-muted">Album contoh · 1 foto. Gambar dibuat dengan AI sebagai ilustrasi, bukan foto anggota kelas sungguhan.</p>
        </section>
        <section x-show="modal === 'notifications'">
            <p class="mb-4 text-xs text-muted">Pemberitahuan contoh untuk pratinjau tampilan.</p>
            <button @click="openModal('announcement')" class="flex w-full items-start gap-3 rounded-2xl bg-sage p-4 text-left"><x-icon name="megaphone" class="mt-1 text-[#526443]" /><span><span class="block text-sm font-semibold">Papan pengumuman kelas</span><span class="mt-1 block text-xs text-muted">Baca kabar terbaru dari pengurus.</span></span></button>
            <button @click="openModal('birthdays')" class="mt-3 flex w-full items-start gap-3 rounded-2xl bg-[#fff3c8] p-4 text-left"><x-icon name="cake" class="mt-1 text-orange-dark" /><span><span class="block text-sm font-semibold">Kalender ulang tahun kelas</span><span class="mt-1 block text-xs text-muted">Lihat hari spesial yang dibagikan teman sekelas.</span></span></button>
        </section>
        <section x-show="modal === 'settings'" class="space-y-4 text-sm leading-7">
            <p>Ini pratinjau dashboard <strong>Digital Class</strong>, dibuat dengan Laravel, Livewire, dan Tailwind CSS.</p>
            <div class="rounded-2xl bg-[#fff7e9] p-4"><p class="font-semibold">Coba dengan bebas</p><p class="mt-1 text-muted">Notifikasi dan galeri masih berupa pratinjau.</p></div>
            <p class="text-muted">Akun, profil, cerita, komentar, suka, agenda, konfirmasi kehadiran, suara polling, dan pengumuman sudah tersimpan. Kalender ulang tahun mengikuti data serta privasi profil. Buka Profilku untuk mengubah foto, cerita pribadi, dan privasi. Cerita kelas hanya terlihat oleh anggota kelas yang sama.</p>
        </section>
    </div>
</dialog>
