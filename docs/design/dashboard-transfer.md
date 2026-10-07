# Dashboard: pemindahan ke folder utama

Pada 6 Oktober 2026, hasil dashboard dari worktree Codex disalin secara selektif ke `C:/laragon/www/website kelas` atas permintaan pemilik proyek. Pengerjaan lanjutan menggunakan folder utama tersebut.

- Halaman aktif: `src/resources/views/pages/dashboard.blade.php`, mengikuti penamaan tanpa emoji di folder utama.
- Layout khusus dashboard: `src/resources/views/layouts/dashboard.blade.php`, dipilih lewat atribut Livewire `Layout`.
- Layout lama `src/resources/views/layouts/app.blade.php` tetap dipertahankan untuk halaman lain.
- Route, model, migration, seeder, konfigurasi, dependency manifests, `.env`, gambar lama, dan halaman feed/events/gallery/members/profile tidak ditimpa.
- File yang dihapus di worktree tidak ikut dihapus di folder utama.
- 18 file hasil kerja disalin. Hash seluruh file tracked/untracked yang sudah ada di luar daftar salinan diperiksa setelah penyalinan; tidak ada perubahan tak terduga.
- Versi sebelum penimpaan dan manifest transfer tersimpan di `C:/Users/USER/.codex/backups/website-kelas/20261006-114233`.
- Tidak dilakukan commit, push, migration, atau seeding.

Dashboard tetap berupa demo interaktif tanpa penyimpanan database. Aset dan mockup tetap ada di direktori proyek. Folder worktree asal masih dipertahankan.
