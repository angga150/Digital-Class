# Event Countdown

## Status

Tahap Agenda Kelas diimplementasikan (15 Oktober 2026) di direktori utama `C:/laragon/www/website kelas`, dengan Laravel di `src/`. Stack tetap Livewire dan Tailwind, mengikuti tampilan scrapbook dashboard.

## Implementasi saat ini

- `/events`: daftar agenda dengan filter Akan datang, Berlangsung, Selesai, Dibatalkan, dan Semua; pagination 9 kegiatan per halaman.
- `/events/{event}`: detail jadwal, lokasi, deskripsi, tautan kegiatan, jumlah konfirmasi hadir, dan pilihan kehadiran milik pengguna.
- Pengurus kelas (`admin`) dengan email terverifikasi dapat membuat agenda, mengedit agenda yang belum dimulai, membatalkan agenda yang akan/sedang berlangsung, dan menghapus agenda. Anggota dengan kelas aktif dapat membaca; email terverifikasi diperlukan untuk mengisi kehadiran.
- Pilihan kehadiran: Hadir, Mungkin, atau Tidak hadir. Pilihan dapat diubah atau dibatalkan sebelum kegiatan dimulai. Tidak ada daftar nama atau pilihan kehadiran anggota lain pada UI.
- Widget dashboard mengambil agenda terdekat yang belum berakhir, termasuk yang sedang berlangsung. Agenda dibatalkan dan terhapus tidak muncul. Tidak ada kegiatan yang cocok → tampil pesan kosong yang ramah.
- Status dihitung dari waktu mulai, waktu selesai, dan `cancelled_at` saat dibaca. Halaman dan widget melakukan polling setiap 60 detik selama aktif; tidak perlu cron untuk memindahkan status.

## Data, izin, dan validasi

Tabel `events` dan `event_rsvps` yang sudah ada dipertahankan. Waktu mulai tetap memakai kolom `event_date`, bukan mengganti nama menjadi `starts_at`. Migration tambahan hanya menambah `ends_at`, `cancelled_at`, `deleted_at`, dan indeks kelas/tanggal. Agenda lama tanpa jam selesai dianggap selesai saat waktu mulainya lewat.

Input dan tampilan menggunakan **WIB (Asia/Jakarta)**; penyimpanan menggunakan UTC. Koneksi MySQL memakai zona waktu sesi `+00:00`. Karena skema lama memakai `TIMESTAMP`, tanggal dibatasi sampai 19 Januari 2038 pukul 10:14 WIB pada input dan server, mengikuti [rentang TIMESTAMP MySQL 8.4](https://dev.mysql.com/doc/refman/8.4/en/datetime.html). Jam mulai harus di masa depan dan jam selesai harus sesudah jam mulai. Keduanya wajib untuk agenda baru maupun penyimpanan edit.

Judul maksimal 150 karakter, deskripsi 3.000, lokasi dan tautan masing-masing 255. Jenis kegiatan mengikuti skema yang tersedia: akademik, kumpul bareng, deadline, atau lainnya. Tautan hanya menerima HTTP/HTTPS, konten di-escape oleh Blade, dan lokasi kosong menampilkan “Lokasi menyusul”. Countdown menampilkan hari jika minimal 24 jam lagi, lalu jam/menit untuk waktu yang lebih dekat.

`Event` dibatasi oleh global scope kelas aktif; `EventRsvp` mengikuti scope induknya. Policy memeriksa ulang membership, role, status verifikasi, dan status agenda pada setiap mutasi, termasuk form yang sudah lama terbuka. Semua agenda saat ini bersifat `class_only`; ID lintas kelas tidak dapat dipakai untuk membaca atau mengubah data.

RSVP memakai unique constraint `(event_id, user_id)`, transaksi, dan lock pada agenda. Mengirim pilihan yang sama berulang kali tidak menambah baris. Angka kehadiran di UI dihitung dari relasi dengan status `going`, sehingga tidak bergantung pada counter data contoh. Pembatalan acara mempertahankan agenda dan RSVP sebagai catatan; penghapusan memakai soft-delete. Belum ada tombol pemulihan atau pembukaan ulang kegiatan dibatalkan.

Unggah gambar, pengaturan visibility per agenda, ekspor kalender, dan notifikasi/pengingat belum termasuk tahap ini. Role Super Admin pada rancangan awal di bawah belum tersedia di skema role saat ini. File demo `resources/views/pages/events.blade.php` tetap disimpan untuk konteks; route aktif memakai `App/Livewire/Agenda` dan `resources/views/livewire/agenda`.

## Menjalankan dan memverifikasi

Dari `src/`, gunakan `php artisan migrate`, `npm run build`, dan `php artisan test`. Migration sudah diterapkan pada database lokal setelah pencadangan konsisten ke `C:/Users/USER/.codex/backups/website-kelas/auth-20261015/database-before-agenda-20261015-111420.sqlite`. Tidak menjalankan ulang seeder atau menghapus data lama.

Seluruh suite lulus: **42 tes, 289 assertions**, termasuk 14 tes Agenda. Cakupannya: izin pengurus/anggota, email terverifikasi, validasi waktu dan batas TIMESTAMP, konversi WIB/UTC, status tepat pada waktu mulai/selesai, kompatibilitas agenda lama, filter, widget dashboard, RSVP berulang, form kedaluwarsa, pembatalan/soft-delete, isolasi kelas, dan escaping konten. Build aset serta Pint lulus. Tes database memakai SQLite in-memory; integrasi terhadap server MySQL belum diuji.

Server lokal tidak dijalankan dalam tahap ini sesuai permintaan pengguna. Render halaman diuji melalui HTTP/Livewire tests; pemeriksaan visual interaktif Agenda di browser belum dilakukan.

## Rancangan awal untuk pengembangan lanjutan

Bagian berikut dipertahankan sebagai konteks rancangan awal. Jika berbeda dengan implementasi, gunakan catatan di atas; misalnya status saat ini dihitung langsung dan tidak memakai scheduled task harian.

## Tujuan

Supaya event penting (UTS, UAS, wisuda, acara kelas) tidak lagi hilang ditelan chat grup — semua tercatat di satu tempat dengan countdown yang jelas.

## User Flow

```
Class Admin membuat event
  ↓
Isi judul, deskripsi, tanggal+waktu, lokasi, gambar (opsional)
  ↓
Event muncul di Dashboard (countdown) & halaman daftar Event
  ↓
Member lihat detail, tidak bisa edit
  ↓
Tanggal event lewat → status otomatis jadi "completed"
```

## Business Rules

- Hanya Class Admin (dan Super Admin) yang bisa membuat/edit/hapus event.
- Tanggal & waktu disimpan dalam UTC, dikonversi ke waktu lokal saat ditampilkan.
- Countdown menampilkan hitungan **hari** untuk event yang masih >7 hari lagi, dan **jam:menit** untuk event <24 jam — supaya tidak berisik menampilkan detik/menit untuk event yang masih jauh.
- Status event: `upcoming` → `ongoing` → `completed`, atau `cancelled`.

## Database

Tabel `events` — lihat [`../04-database.md`](../04-database.md).

## Backend

- Validasi: `starts_at` tidak boleh di masa lalu saat event dibuat (kecuali admin sengaja membuat entri retroaktif — di luar scope MVP).
- Scheduled task harian mengubah status event yang tanggalnya sudah lewat menjadi `completed`.

## Frontend

- Widget countdown di Dashboard
- Halaman daftar event, bisa difilter berdasarkan status
- Form buat/edit event (hanya muncul untuk Class Admin)

## Permission

Create/update/delete: Class Admin & Super Admin. Read: semua role login, sesuai `visibility`.

## Validation

- `title` wajib diisi
- `starts_at` wajib diisi, format tanggal+waktu valid
- `location` boleh kosong (tampilkan "Lokasi menyusul" kalau kosong, bukan string kosong)

## Edge Cases

- Event tanpa lokasi (online/TBA) → tampilkan pesan yang jelas, bukan kosong begitu saja
- Event dibatalkan (`cancelled`) → tetap tampil di daftar dengan label jelas, tidak dihapus dari database

## Testing

Cukup test manual: pastikan hanya Class Admin yang bisa membuat event (Member coba akses harus ditolak).

## Related Features

Dashboard, Academic Corner
