# Notifikasi dalam aplikasi

Diimplementasikan 16 Oktober 2026. `/notifications` menyediakan daftar pribadi, filter semua/belum dibaca, pagination 12 item, tandai dibaca/belum dibaca, serta tandai semua dibaca. Tombol Buka menandai item lalu menuju sumbernya; notifikasi komentar membuka bagian komentar cerita. Lonceng dashboard dan header halaman anggota menampilkan jumlah belum dibaca (badge maksimal `99+`). Data diperbarui tiap 60 detik saat halaman aktif.

## Aktivitas yang dicatat

- Suka baru pada cerita/pengumuman → pemilik post, satu catatan per pasangan post/pemberi suka.
- Komentar baru → pemilik post, satu catatan per komentar.
- Pengumuman baru dari papan kabar → anggota kelas selain pengirim. Edit, pin, atau melepas pin tidak mengirim ulang.

Aktivitas sendiri dan simpanan cerita tidak membuat notifikasi. Tidak ada email, push, notifikasi ulang tahun/agenda/polling, mention, atau impor aktivitas lama. Notifikasi baru tercatat ketika aksi aplikasi dilakukan setelah fitur tersedia; migration tidak mengirim atau membuat notifikasi massal dari data lama.

## Penyimpanan dan akses

Tabel tambahan `class_notifications` menyimpan ID penerima/kelas/aktor/sumber, jenis, event key unik per penerima, waktu baca, dan timestamps. Tidak menyalin nama, email, isi post, atau isi komentar. Label bersifat umum dan ringkasan post diambil dari sumber yang masih dapat diakses. Nickname privat tidak bocor lewat notifikasi.

Penulisan melalui `ClassActivity` berada dalam transaksi aksi asal. Penerima ditentukan dari membership kelas aktif di server, bukan input klien. Unique constraint mencegah penggandaan aktivitas yang sama. Query baca, hitung badge, buka, dan tandai baca memakai scope pemilik + kelas aktif + sumber yang masih tersedia. Pengguna tidak dapat mengubah notifikasi penerima lain.

Post terhapus, komentar terhapus, atau suka yang sudah dibatalkan membuat notifikasi terkait tersembunyi, termasuk dari badge. Data riwayat tetap tersimpan saat soft-delete. Jika sumber dipulihkan, catatan dapat muncul lagi; batal suka lalu suka lagi memakai catatan yang sama beserta status baca sebelumnya, tanpa membuat notifikasi baru. Ketika pengguna pindah/kehilangan kelas aktif, notifikasi kelas lama tidak ditampilkan. Belum ada hapus notifikasi, pengaturan mute, atau retensi otomatis.

## Verifikasi dan database lokal

Verifikasi akhir ketiga fitur: **88 tes, 664 assertions lulus**; build aset dan Pint lulus. Termasuk 4 tes Simpan Cerita, 5 tes Pencarian Kelas, dan 7 tes Notifikasi.

Tes meliputi persistensi suka/komentar/pengumuman, deduplikasi, pengecualian aktivitas sendiri, privasi penerima/aktor, tanda baca massal, deep link komentar, soft-delete, pencabutan membership, escaping, dan count badge. Seluruh test suite memakai SQLite in-memory; belum ada integration test server MySQL atau browser interaktif. Server tidak dinyalakan.

Migration lokal sudah diterapkan sesudah backup konsisten: `C:/Users/USER/.codex/backups/website-kelas/features-20261016/before-notifications-20261016-073856.sqlite`. Backup sebelum migration Simpan Cerita: `C:/Users/USER/.codex/backups/website-kelas/features-20261016/before-bookmarks-20261016-072847.sqlite`. Data dan file konteks lama dipertahankan, tanpa menjalankan seeder.
