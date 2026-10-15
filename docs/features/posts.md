# Social Feed (Posts)

## Status

Tahap cerita teks diimplementasikan (15 Oktober 2026): tulis, edit, hapus lunak, komentar, suka, filter Ceritaku, dan pemuatan cerita sebelumnya. Komponen Livewire yang sama dipakai di dashboard dan `/feed`.

Gambar, pilihan visibility per cerita, report/moderasi dengan audit log, dan infinite scroll otomatis belum termasuk tahap ini. Semua cerita saat ini bersifat `class_only`; pemuatan lanjutan menggunakan tombol agar tetap mudah diakses.

## Catatan implementasi saat ini

- Memakai tabel yang sudah berjalan: `posts`, `post_comments`, `post_likes`. Rancangan komentar/reaction polymorphic di bawah adalah arah lanjutan, belum migrasi skema.
- Migration tambahan hanya menambah `deleted_at` pada post dan komentar; data lama dipertahankan. Tidak menjalankan ulang seeder. Cerita seed lama tetap tampil jika ada pada kelas pengguna.
- `Post` memiliki global scope kelas aktif; komentar dan suka mengikuti scope induk post, termasuk penyembunyian saat induk terhapus.
- Semua mutasi melalui Policy dan memerlukan email terverifikasi. Hanya penulis boleh edit; penulis atau pengurus kelas boleh menghapus. Role pengurus mengikuti skema saat ini (`admin`).
- Konten teks maksimum 2.000 karakter; komentar 500. Whitespace kosong ditolak dan output di-escape oleh Blade. Posting dibatasi 5/menit, komentar 15/menit per akun.
- Like memakai desired state (suka/tidak), unique constraint `(post_id, user_id)`, dan transaksi dengan lock induk, sehingga pengulangan request tidak menambah reaction ganda. Counter UI dihitung dari relasi, bukan angka seed.
- Edit, hapus, komentar, dan suka memeriksa ulang izin saat aksi berjalan. Form komentar yang tertinggal setelah post dihapus ditolak.
- Data penulis melalui presenter profil: nickname privat tidak bocor di feed. Email tidak dikirim ke tampilan. Avatar feed saat ini berupa inisial.
- Soft-delete mempertahankan data untuk pemulihan administratif; belum ada tombol restore di UI. Jangan menganggapnya recycle bin pengguna.
- Halaman lama `resources/views/pages/feed.blade.php` tetap disimpan untuk konteks, tetapi route aktif memakai `App/Livewire/Stories/Feed.php` dan `resources/views/livewire/stories/feed.blade.php`.
- Modal dan state contoh cerita/komentar di dashboard diganti oleh komponen persisten. [Agenda](./events.md) dan [Polling](./polls.md) juga sudah terhubung ke database. Ulang tahun, galeri, dan notifikasi masih contoh yang diberi penjelasan.

Jalankan `php artisan migrate`, `npm run build`, dan `php artisan test` dari `src/`. Test memakai SQLite in-memory. Database lokal dicadangkan sebelum migration ke `C:/Users/USER/.codex/backups/website-kelas/auth-20261015/database-before-stories.sqlite`.

Cakupan tes: simpan dan render dashboard, validasi konten, akun belum verified, like idempotent, edit milik sendiri, hapus oleh pengurus, ID lintas kelas, komentar pada post terhapus, privasi penulis, XSS, filter/pemuatan lanjutan, dan rate limit. Pemeriksaan browser mencakup feed dashboard, navigasi, komentar, dan persistensi suka setelah reload.

Bagian berikut mempertahankan rancangan fitur lengkap untuk konteks pengembangan berikutnya.

## Tujuan

Ruang ekspresi santai untuk anggota kelas — versi ringan dari media sosial, bukan Facebook/Instagram penuh.

## User Flow

```
Member buat post (text/gambar/memory), pilih visibility
  ↓
Post muncul di feed, urut terbaru, infinite scroll
  ↓
User lain react (like) dan comment
  ↓
User bisa report post yang bermasalah
  ↓
Author bisa hapus post sendiri (soft delete)
```

## Business Rules

- Post tidak butuh approval sebelum tayang — moderasi bersifat **reaktif** (lewat report), bukan **preventif** (approval dulu), supaya tidak menghambat spontanitas (prinsip Community Owned).
- Post kosong (tanpa teks & tanpa media) tidak boleh dibuat.
- Satu user hanya bisa react sekali ke post yang sama (dicegah lewat database constraint, bukan cuma validasi tampilan).

## Database

Tabel `posts`, `comments` (polymorphic), `reactions` (polymorphic), `reports` (polymorphic). Lihat [`../04-database.md`](../04-database.md).

## Backend

- `PostPolicy@create` — semua role login berstatus `active` boleh buat post.
- `PostPolicy@delete` — author sendiri, atau Moderator/Class Admin (moderasi).
- Reaction dicegah dobel lewat unique constraint database (`reactable_id`, `reactable_type`, `user_id`, `type`).

## Frontend

- Feed dengan infinite scroll (bukan pagination angka — lebih natural untuk feed)
- Form buat post (modal atau area di atas feed)
- Tombol react & comment di tiap post
- Tombol report (ikon kecil, tidak mencolok tapi mudah ditemukan)

## Permission

Create: semua role login berstatus `active`. Delete: author sendiri atau Moderator/Class Admin. Report: semua role login.

## Validation

- Post harus punya `body` atau `media_path` (tidak boleh dua-duanya kosong)
- Upload gambar mengikuti aturan file upload di [`../03-architecture.md`](../03-architecture.md) §7

## Edge Cases

- Post yang di-soft-delete tetap tercatat di `audit_logs` kalau sebelumnya pernah di-report — untuk keperluan moderasi
- Comment di post yang sudah dihapus → tidak bisa ditambah komentar baru

## Testing

**Wajib ditest**: reaction tidak bisa dobel oleh user yang sama, dan hanya author/moderator yang bisa hapus post orang lain.

## Related Features

Dashboard (aktivitas terbaru), Moderation, Member Profile
