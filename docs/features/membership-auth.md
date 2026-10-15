# Akun, keanggotaan, dan profil

Update lanjutan: [Cerita Kelas](./posts.md) sekarang menyimpan cerita teks, komentar, dan suka. Penjelasan batas sosial demo di catatan tahap akun di bawah adalah kondisi sebelum fitur cerita ditambahkan; agenda, polling, galeri, dan notifikasi masih contoh.

Implementasi 15 Oktober 2026 berada di folder utama `C:/laragon/www/website kelas`, aplikasi Laravel di `src/`. Stack tetap Laravel, Livewire, Tailwind, dan Alpine untuk demo dashboard. Tidak ada paket autentikasi baru; session, password broker, dan email verification memakai Laravel.

## Yang sudah bekerja

- `/register`: kode undangan kelas aktif wajib, akun dan membership dibuat dalam satu transaksi, role selalu `member`.
- `/login`, POST `/logout`: session diregenerasi saat login dan dibatalkan saat logout; login dibatasi berdasarkan email/IP dan throttle route.
- `/join`: akun tanpa kelas aktif bisa bergabung memakai undangan. Akun yang sudah mempunyai kelas aktif diarahkan ke dashboard.
- `/forgot-password`, `/reset-password/{token}`: tautan pemulihan Laravel dengan token sekali pakai dan throttle. Respons permintaan tidak mengungkap apakah email terdaftar.
- `/email/verify`: verifikasi email dengan signed URL, resend dibatasi. Unggah avatar memerlukan email terverifikasi.
- `/profile`: nama, nickname, bio, hobi/favorit dipisahkan koma, fun fact, tanggal lahir opsional, visibility, foto.
- `/members`: data database dengan pencarian nama lengkap, pagination, dan detail anggota. Email tidak ditampilkan ke anggota lain.
- Pengurus dapat melihat kode undangan yang sudah ada melalui bagian “Undang teman sekelas” di profilnya. Pembuatan/rotasi kode belum memiliki UI admin.
- Dashboard menggunakan identitas pengguna yang login. Menu Teman Kelas dan Profilku menuju halaman nyata.

## Data dan akses

Migration baru hanya menambahkan tabel `profiles` satu-satu dengan `users`. Tabel lama dan datanya tidak dihapus. `class_members` dan role `admin/member` dipertahankan agar sesuai skema yang sudah berjalan; nama `class_memberships` dan empat role pada rancangan awal belum dimigrasikan.

`CurrentClass` memilih membership paling awal milik pengguna pada kelas aktif. Belum ada pemilih banyak kelas. `ClassMember` memakai global scope fail-closed; hanya resolver membership pengguna dan proses join yang secara eksplisit melewati scope. Model sosial lama belum diubah karena UI sosial masih demo.

`ProfilePolicy` membatasi update pada pemilik dengan kelas aktif. Livewire mengambil user dari session setiap save dan tidak menerima target user/profile ID. Middleware keanggotaan juga didaftarkan sebagai persistent middleware Livewire.

`MemberProfile` membentuk data tampilan di server: `class_only`, `admin_only`, atau `private`. Profil privat menyembunyikan foto, nickname, bio, hobi, favorit, fun fact, dan ulang tahun; nama lengkap dan role tetap muncul di direktori kelas. Tahun lahir tidak ditampilkan secara default. Pencarian hanya menggunakan nama lengkap agar field privat tidak bisa ditebak lewat hasil pencarian.

Avatar maksimal 5 MB, JPG/JPEG/PNG/WebP, disimpan di disk `local` privat. Endpoint avatar memeriksa kelas dan visibility setiap request, dengan `Cache-Control: private, no-store`. Foto lama dibersihkan setelah penyimpanan baru berhasil. Tidak perlu `storage:link` untuk avatar.

Field nickname/birthday lama pada `class_members` tetap utuh, tetapi tidak otomatis dipublikasikan sebagai profil baru. Pengguna mengisi sendiri data profilnya. Belum ada badge otomatis, pengingat ulang tahun, atau penyimpanan sosial.

## Menjalankan lokal

Jalankan dari `src/`:

```powershell
php artisan migrate
npm run build
php artisan serve --host=127.0.0.1 --port=8018
```

Database lokal yang ditemukan masih SQLite; driver tidak diganti. Migration memakai Schema Laravel agar kompatibel dengan rencana MySQL. Jangan gunakan `migrate:fresh` atau menjalankan ulang seeder demo pada database berisi data.

Sebelum migration lokal, salinan database disimpan di `C:/Users/USER/.codex/backups/website-kelas/auth-20261015/database-before-profiles.sqlite`.

Konfigurasi lokal saat implementasi memakai `MAIL_MAILER=log`. Email verifikasi/reset ditulis ke `src/storage/logs/laravel.log`, belum dikirim ke inbox. Atur mail transport dan APP_URL sesuai host sebenarnya sebelum digunakan di luar lokal. Jangan membagikan log karena memuat tautan akun.

File demo profil/anggota lama di `resources/views/pages/` tetap disimpan untuk konteks, tetapi route aktif memakai `App/Livewire/Membership` dan view `resources/views/livewire/membership/`.

## Validasi

`php artisan test` memakai SQLite in-memory pada phpunit.xml, bukan database aplikasi. Cakupan: autentikasi, throttle, kode undangan invalid/inaktif, role injection, join berulang, profil persisten, policy pemilik, privasi profil dan tanggal lahir, pemisahan kelas, avatar privat/validasi/verified, signed verification, reset password, serta kelas dinonaktifkan ketika editor sudah terbuka.

Build aset: `npm run build`. Gaya PHP: `vendor/bin/pint --dirty`. Browser diperiksa pada tampilan HP dan desktop, termasuk login, pencarian anggota, detail profil, dan form profil.
