# Polling

## Status

Tahap Polling Kelas diimplementasikan pada 16 Oktober 2026 di direktori utama `C:/laragon/www/website kelas`, aplikasi Laravel di `src/`. Stack tetap Livewire dan Tailwind.

## Implementasi saat ini

- `/polls` menyediakan daftar dengan filter Masih dibuka, Sudah ditutup, dan Semua; pagination 6 polling per halaman.
- Pengurus dengan email terverifikasi dapat menerbitkan polling dengan pertanyaan, catatan opsional, 2–6 opsi berbeda, batas waktu WIB, dan pengaturan hasil. Pertanyaan/opsi tidak dapat diedit setelah terbit agar arti suara tidak berubah.
- Semua polling baru memakai satu pilihan per akun dan tidak menampilkan identitas pemilih. Suara tidak bisa diganti atau dibatalkan. Pengurus dapat menutup polling lebih awal; belum ada hapus atau buka ulang.
- Anggota aktif dengan email terverifikasi dapat memilih. Pengguna belum terverifikasi tetap dapat membaca dan mendapat tautan verifikasi saat hendak memilih.
- Hasil dapat terlihat langsung, setelah memilih atau polling ditutup, hanya setelah ditutup, atau hanya bagi pengurus. Aturan berlaku di server, termasuk bagi pengurus yang memilih mode “setelah ditutup”. Hasil kosong menampilkan 0 suara dan 0%.
- Widget dashboard menampilkan polling aktif dengan batas waktu terdekat; polling lama tanpa batas waktu ditempatkan setelah polling bertanggal. Link “Semua polling” membuka `/polls`. Polling demo Alpine beserta angka contoh sudah diganti.
- Status ditentukan dari `is_active` dan `ends_at`; pada waktu yang tepat sama dengan deadline, suara baru sudah ditolak. Polling berakhir otomatis tanpa scheduled task. Komponen memperbarui tampilan setiap 60 detik saat aktif.

## Penyimpanan, privasi, dan izin

Memakai tabel `polls`, `poll_options`, dan `poll_votes` yang sudah ada. Deadline tetap bernama `ends_at` sesuai skema berjalan, bukan `closes_at` pada rancangan awal. Input WIB dikonversi ke UTC. Batas maksimum mengikuti kolom TIMESTAMP yang sama dengan [Agenda](./events.md): 19 Januari 2038 pukul 10:14 WIB.

Migration menambah `result_visibility` (default `all` untuk data lama) dan unique constraint `(poll_id, user_id)`. Sebelum mengubah skema, migration memeriksa duplikasi vote lama dan berhenti dengan pesan jika ditemukan; tidak menghapus atau memilih suara lama secara otomatis. Data lokal saat implementasi tidak memiliki suara ganda.

Vote dan penutupan manual memakai transaksi dengan lock pada induk polling. Pilihan harus milik polling yang sama. Membership, email terverifikasi, dan status buka diperiksa ulang pada setiap aksi, sehingga form kedaluwarsa tidak bisa melewati batas waktu. Unique constraint tetap mencegah duplikasi pada level database.

`Poll` memakai global scope kelas aktif; opsi dan vote mengikuti scope induknya. `PollPolicy` mengatur baca, buat, vote, tutup, dan akses hasil. UI menghitung hasil dari relasi vote sebenarnya, bukan angka seed `total_votes`/`votes_count`. Bila hasil belum diizinkan, query opsi tidak mengambil jumlah vote. Komponen hanya menyimpan ID polling terkunci, pilihan input milik pengguna, dan pesan status; daftar identitas pemilih tidak dimuat ke view atau state Livewire.

`user_id` tetap disimpan untuk pencegahan suara ganda; anonim berarti identitas tidak ditampilkan melalui aplikasi, bukan anonimisasi terhadap pengelola database. Pengguna dapat melihat pilihannya sendiri. Polling lama non-anonim juga hanya menampilkan hasil agregat. Polling lama dengan `allow_multiple = true` dipertahankan tetapi tidak menerima suara baru karena MVP ini khusus satu pilihan.

Role pengurus saat ini adalah `admin`; Super Admin pada rancangan awal belum tersedia. Pengaturan hasil tidak bisa diedit setelah terbit. Notifikasi, ekspor, polling publik, pilihan ganda, dan pengubahan suara belum termasuk tahap ini.

## Menjalankan dan memverifikasi

Dari `src/`, jalankan `php artisan migrate`, `npm run build`, dan `php artisan test`. Migration lokal sudah diterapkan setelah backup SQLite konsisten ke `C:/Users/USER/.codex/backups/website-kelas/polls-20261016/database-before-polling-20261015-173640.sqlite`. Tidak menjalankan seeder atau menghapus file konteks lama.

Seluruh suite lulus: **53 tes, 399 assertions**, termasuk 11 tes Polling. Tes fitur mencakup penerbitan dan konversi WIB, validasi opsi/batas tanggal, peran/verifikasi, vote tunggal dan constraint database, opsi polling lain, deadline tepat waktu, penutupan manual dengan form kedaluwarsa, visibility hasil, privasi identitas, counter seed, scope kelas, membership dinonaktifkan, widget, dan escaping konten. Tes memakai SQLite in-memory; belum ada pengujian integrasi pada server MySQL. Build aset dan Pint juga lulus. Server tidak dinyalakan; pemeriksaan halaman memakai HTTP/Livewire tests, belum pemeriksaan visual interaktif di browser.

## Rancangan awal untuk pengembangan lanjutan

Bagian di bawah dipertahankan sebagai konteks. Gunakan catatan implementasi di atas untuk batas fitur yang sudah tersedia.

## Tujuan

Ganti voting manual di grup chat (yang sering rawan curang/hilang) dengan sistem yang hasilnya valid dan bisa dipercaya.

## User Flow

```
Class Admin buat polling: pertanyaan + opsi + deadline + anonim/tidak
  ↓
Member pilih satu opsi
  ↓
Sistem cegah user vote dua kali
  ↓
Polling otomatis tutup saat deadline lewat (atau ditutup manual admin)
  ↓
Hasil ditampilkan sesuai pengaturan visibility hasil
```

## Business Rules

- MVP hanya **single choice** (satu opsi per user) — multiple choice ditunda ke pasca-MVP supaya validasi vote-dobel tetap sederhana.
- Vote **tidak bisa diubah** setelah submit di MVP (ditunda ke pasca-MVP kalau dibutuhkan).
- Polling anonim (`is_anonymous = true`): `user_id` tetap disimpan di database (untuk cegah vote dobel), tapi **tidak pernah ditampilkan** ke siapa pun termasuk Class Admin — hanya angka agregat yang tampil.

## Database

Tabel `polls`, `poll_options`, `poll_votes`. Kombinasi poll+user di-unique-kan untuk cegah vote dobel. Lihat [`../04-database.md`](../04-database.md).

## Backend

- `PollPolicy@create` — Class Admin & Super Admin saja.
- `PollPolicy@vote` — semua role login berstatus `active`.
- Vote setelah `closes_at` lewat **ditolak di server**, bukan cuma tombol di-disable di UI (karena request langsung tetap harus divalidasi).

## Frontend

- Form buat polling (Class Admin)
- Tampilan vote: radio button per opsi, submit sekali
- Tampilan hasil: bar chart sederhana persentase per opsi

## Permission

Create/tutup manual: Class Admin & Super Admin. Vote: semua role login berstatus `active`. Lihat hasil admin-only: dibatasi Policy `viewResults` kalau `result_visibility = admin_only`.

## Validation

- Minimal 2 opsi per polling
- `question` wajib diisi
- Vote wajib memilih salah satu opsi yang valid (bukan opsi dari polling lain)

## Edge Cases

- Semua opsi 0 vote → tampilkan hasil apa adanya (0%), bukan disembunyikan
- Polling ditutup manual sebelum deadline → vote yang masuk setelah itu ditolak

## Testing

**Wajib ditest**: satu user benar-benar tidak bisa vote dua kali (test lewat request langsung, bukan cuma lewat UI), dan identitas voter di polling anonim tidak pernah bocor lewat endpoint manapun.

## Related Features

Dashboard, Moderation
