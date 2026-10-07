<dialog id="dashboard-dialog" x-ref="dialog" @click="if ($event.target === $refs.dialog) closeModal()" @close="modal = ''" aria-labelledby="dialog-title" class="max-h-[85dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto rounded-3xl border border-line bg-white p-0 text-ink shadow-2xl backdrop:bg-black/30">
    <div class="flex items-start justify-between gap-3 border-b border-line p-5 sm:p-6">
        <div><p class="mb-1.5 text-[10px] font-bold uppercase tracking-widest text-orange-dark">Digital Class · mode demo</p><h2 id="dialog-title" x-ref="dialogTitle" tabindex="-1" class="modal-heading" x-text="modalTitle"></h2></div>
        <button @click="closeModal()" class="icon-button -mr-2 -mt-1" aria-label="Tutup dialog"><x-icon name="close" /></button>
    </div>
    <div class="p-5 sm:p-6">
        <section x-show="modal === 'compose'">
            <p class="mb-5 text-sm leading-6 text-muted">Cerita sederhana juga layak dibagi. Tulisanmu hanya tampil dalam demo ini dan hilang setelah halaman dimuat ulang.</p>
            <form @submit.prevent="publish()">
                <label for="story-draft" class="mb-2 block text-sm font-semibold">Ceritamu</label>
                <textarea id="story-draft" x-ref="draft" x-model="draft" maxlength="500" rows="5" required class="field resize-y" placeholder="Hari ini di kelas..." :aria-invalid="!!draftError" aria-describedby="draft-error"></textarea>
                <div class="mt-2 flex items-start justify-between gap-2"><p id="draft-error" class="text-xs text-red-700" role="alert" x-text="draftError"></p><span class="shrink-0 text-xs text-muted"><span x-text="draft.length"></span>/500</span></div>
                <p class="mt-3 text-xs text-muted">Unggah foto tersedia setelah fitur galeri terhubung.</p>
                <div class="mt-6 flex justify-end gap-2"><button type="button" @click="closeModal()" class="quiet-button text-muted">Batal</button><button type="submit" class="primary-button"><x-icon name="pen" :size="16" />Bagikan di demo</button></div>
            </form>
        </section>
        <section x-show="modal === 'comments'">
            <p class="mb-4 text-xs text-muted">Komentar demo hanya berlaku selama halaman ini terbuka.</p>
            <div class="mb-5 max-h-64 space-y-3 overflow-auto">
                <template x-for="comment in (currentPost?.comments ?? [])" :key="comment.id"><div class="rounded-2xl bg-paper p-4"><p class="mb-1 text-xs font-bold" x-text="comment.author"></p><p class="whitespace-pre-wrap break-words text-sm leading-6" x-text="comment.text"></p></div></template>
                <p x-show="!currentPost?.comments.length" class="rounded-xl bg-paper p-4 text-sm text-muted">Belum ada komentar. Mulai obrolannya, yuk!</p>
            </div>
            <form @submit.prevent="addComment()"><label for="comment-draft" class="mb-2 block text-sm font-semibold">Tambahkan komentar</label><textarea id="comment-draft" x-ref="comment" x-model="commentDraft" required maxlength="300" rows="2" class="field" placeholder="Tulis sesuatu yang baik..." :aria-invalid="!!commentError" aria-describedby="comment-error"></textarea><p id="comment-error" x-text="commentError" role="alert" class="mt-2 text-xs text-red-700"></p><button class="primary-button mt-3" type="submit">Kirim komentar</button></form>
        </section>
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
        <section x-show="modal === 'event'" class="space-y-5">
            <span class="inline-flex rounded-full bg-sage px-3 py-1 text-xs font-semibold text-[#526443]">3 hari lagi · contoh agenda</span>
            <h3 class="text-xl font-bold">Presentasi kelompok</h3>
            <p class="text-sm leading-6 text-muted">Siapkan hasil kerja kelompok dan bahan presentasi. Setiap kelompok mendapat waktu untuk berbagi dan tanya jawab.</p>
            <div class="space-y-3 rounded-2xl bg-paper p-4 text-sm"><p class="flex items-center gap-3"><x-icon name="calendar" />{{ now('Asia/Jakarta')->addDays(3)->locale('id')->translatedFormat('l, j F Y') }}</p><p class="flex items-center gap-3"><x-icon name="clock" />09.00 WIB</p><p class="flex items-center gap-3"><x-icon name="pin" />Ruang B.204</p></div>
        </section>
        <section x-show="modal === 'memory'">
            <img src="{{ asset('img/dashboard/campus-friends.png') }}" width="1774" height="887" alt="Foto ilustrasi AI, lima mahasiswa berbincang bersama di taman kampus" class="w-full rounded-2xl">
            <p class="mt-4 text-sm leading-6">Obrolan setelah kelas yang selalu kepanjangan. Hal kecil begini yang nanti paling kita kangenin.</p>
            <p class="mt-4 rounded-xl bg-paper p-3 text-xs leading-5 text-muted">Album contoh · 1 foto. Gambar dibuat dengan AI sebagai ilustrasi, bukan foto anggota kelas sungguhan.</p>
        </section>
        <section x-show="modal === 'members'">
            <p class="mb-5 text-sm text-muted">Kenalan dulu, yuk. Semua profil berikut adalah data contoh.</p>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ([['Angga', 'A', 'orange', 'Suka ngulik web'], ['Nadia', 'N', 'lilac', 'Pengumpul cerita'], ['Rani', 'R', 'sage', 'Tim foto bareng'], ['Bima', 'B', 'sage', 'Teman diskusi']] as [$name, $initials, $tone, $bio])
                <div class="flex items-center gap-3 rounded-2xl border border-line p-4"><x-avatar :initials="$initials" :tone="$tone" /><div><h3 class="text-sm font-semibold">{{ $name }}</h3><p class="mt-1 text-xs text-muted">{{ $bio }}</p></div></div>
                @endforeach
            </div>
        </section>
        <section x-show="modal === 'profile'" class="text-center">
            <x-avatar initials="A" class="mx-auto mb-4 size-20 text-2xl" /><h3 class="text-xl font-bold">Angga</h3><p class="mt-2 text-sm text-muted">Suka ngulik web, kopi, dan cerita setelah kelas.</p>
            <span class="mt-5 inline-flex items-center gap-2 rounded-full bg-[#fff3c8] px-4 py-2 text-xs font-semibold text-[#886321]"><x-icon name="star" :size="16" />Contoh badge: Langkah pertama</span>
            <p class="mt-6 text-xs leading-6 text-muted">Profil contoh. Login, edit profil, dan badge otomatis belum diaktifkan.</p>
            <button @click="openModal('members')" class="quiet-button mt-4">Kenalan dengan teman kelas <x-icon name="arrow" :size="16" /></button>
        </section>
        <section x-show="modal === 'notifications'">
            <p class="mb-4 text-xs text-muted">Pemberitahuan contoh untuk pratinjau tampilan.</p>
            <button @click="openModal('announcement')" class="flex w-full items-start gap-3 rounded-2xl bg-sage p-4 text-left"><x-icon name="megaphone" class="mt-1 text-[#526443]" /><span><span class="block text-sm font-semibold">Ada perubahan ruang kelas</span><span class="mt-1 block text-xs text-muted">Baca info ruang B.204</span></span></button>
            <button @click="openModal('birthdays')" class="mt-3 flex w-full items-start gap-3 rounded-2xl bg-[#fff3c8] p-4 text-left"><x-icon name="cake" class="mt-1 text-orange-dark" /><span><span class="block text-sm font-semibold">Rani ulang tahun 2 hari lagi</span><span class="mt-1 block text-xs text-muted">Jangan lupa kasih ucapan, ya!</span></span></button>
        </section>
        <section x-show="modal === 'settings'" class="space-y-4 text-sm leading-7">
            <p>Ini pratinjau dashboard <strong>Digital Class</strong>, dibuat dengan Laravel, Livewire, dan Tailwind CSS.</p>
            <div class="rounded-2xl bg-[#fff7e9] p-4"><p class="font-semibold">Coba dengan bebas</p><p class="mt-1 text-muted">Cerita, komentar, tanda suka, dan polling hanya berubah di halaman ini. Semua kembali ke data contoh saat halaman dimuat ulang.</p></div>
            <p class="text-muted">Data anggota dan foto bersifat ilustratif. Login, penyimpanan database, unggah foto, serta pengaturan privasi akan dihubungkan pada tahap fitur berikutnya.</p>
        </section>
    </div>
</dialog>
