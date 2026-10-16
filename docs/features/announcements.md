# Papan Pengumuman Kelas

## Status

Diimplementasikan pada 16 Oktober 2026 di direktori utama `C:/laragon/www/website kelas`, aplikasi Laravel di `src/`. Stack tetap Livewire dan Tailwind dengan tampilan sage/cream mengikuti dashboard.

## Perilaku saat ini

- `/announcements`: papan kabar dengan filter Semua kabar/Disematkan dan pagination 8 pengumuman per halaman.
- `/announcements/{post}`: tautan detail permanen untuk membaca isi lengkap dan waktu terbit/perubahan dalam WIB.
- Pengurus (`admin`) yang emailnya terverifikasi dapat menerbitkan, mengedit, menyematkan/melepas sematan, dan menghapus pengumuman di kelasnya. Pengurus lain boleh mengelola pengumuman yang sama; identitas penulis awal tetap dipertahankan.
- Isi wajib berupa teks nonkosong, maksimal 2.000 karakter. Awal tulisan menjadi ringkasan banner. Tidak ada judul terpisah atau unggah lampiran pada tahap ini.
- Banner dashboard memprioritaskan pengumuman disematkan, lalu waktu terbit dan ID terbaru. Beberapa pengumuman boleh disematkan; edit isi tidak mengubah urutan waktu terbit. Jika tidak ada sematan, banner menampilkan pengumuman terbaru. Jika kosong, tampil tautan ramah menuju papan kabar.
- Semua anggota kelas aktif boleh membaca, termasuk yang belum verifikasi email. Pengumuman juga tetap muncul di Cerita Kelas, dengan label dan tautan detail; suka serta komentar memakai fitur cerita yang sudah ada.
- Papan, detail, dan banner memperbarui data setiap 60 detik ketika aktif. [Pencarian Kelas](./search.md) mencari pengumuman asli. Penerbitan baru kini menghasilkan [notifikasi dalam aplikasi](./notifications.md) bagi teman sekelas; edit/sematan tidak mengirim ulang dan tidak ada email/push.

## Penyimpanan dan izin

Memakai tabel `posts` yang sudah ada, dengan `type = announcement` dan `is_pinned`. Tidak ada migration atau perubahan skema baru. Data pengumuman seed lama tetap dipertahankan dan dapat tampil pada banner sesuai prioritas; pengurus bisa mengedit atau menghapusnya lewat UI. Tahap ini tidak menjalankan seeder, mengubah data lama otomatis, atau menghapus file konteks.

Scope kelas dan soft-delete pada `Post` tetap berlaku. Semua mutasi memeriksa `PostPolicy`, membership aktif, role admin, serta verifikasi email di server. ID milik kelas lain dan ID post yang bukan pengumuman ditolak pada papan kabar. Edit, pin, dan hapus dilakukan dalam transaksi dengan lock pada post; ID editor dikunci di Livewire.

Policy update/delete untuk pengumuman juga berlaku lewat Cerita Kelas. Penulis yang sudah diturunkan menjadi anggota tidak bisa mengubah atau menghapus pengumuman lewat jalur feed. Aturan edit/hapus cerita biasa tetap seperti sebelumnya.

Penghapusan menggunakan soft-delete: post, komentar, dan suka dipertahankan di database, tetapi pengumuman tidak lagi terlihat di banner, papan, detail, atau feed. Belum ada restore di UI. Form edit yang tertinggal setelah post dihapus ditolak. Status sematan memakai nilai tujuan (true/false), sehingga permintaan berulang tidak membalik status tanpa sengaja.

Konten ditampilkan sebagai teks yang di-escape, bukan HTML. Papan dan detail tidak menampilkan email atau nickname privat penulis. Identitas penulis di feed tetap mengikuti presenter profil. Belum ada status draft, penjadwalan, kedaluwarsa otomatis, read receipt untuk pengurus, atau audit riwayat revisi. Status baca notifikasi hanya tersedia bagi penerimanya sendiri.

## Verifikasi

Seluruh suite lulus: **72 tes, 548 assertions**, termasuk 9 tes Pengumuman. Build aset dan Pint juga lulus.

Jalankan `php artisan test` dan `npm run build` dari `src/`. Tes mencakup penerbitan/edit, aturan admin dan email terverifikasi, validasi teks, prioritas sematan, soft-delete dan form kedaluwarsa, isolasi kelas/jenis post, penulis yang kehilangan role, jalur mutasi feed, kelas dinonaktifkan, escaping, dan privasi penulis. Tes menggunakan SQLite in-memory.

Server lokal tidak dinyalakan. Halaman diperiksa melalui HTTP/Livewire tests; tampilan browser interaktif belum diperiksa. Tidak diperlukan migration baru.
