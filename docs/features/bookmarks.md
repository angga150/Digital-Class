# Simpan Cerita

Diimplementasikan 16 Oktober 2026. Tombol Simpan pada feed menyimpan cerita/pengumuman ke daftar pribadi, tersedia pada filter Tersimpan dan `/feed?filter=saved`. Email terverifikasi diperlukan untuk menyimpan atau melepas simpanan. Pengguna lain, termasuk pengurus, tidak melihat daftar atau jumlah simpanan milik seseorang.

Tabel baru `post_bookmarks` memakai unique `(post_id, user_id)`. Mutasi memakai nilai tujuan, transaksi, lock induk, dan policy interaksi post. Scope hanya mengizinkan simpanan milik pengguna pada post kelas aktif yang belum dihapus. Soft-delete post menyembunyikan simpanan tanpa menghapus riwayatnya. Tidak menyalin isi cerita; perubahan isi mengikuti post asli.

Permalink `/feed/{postId}` menampilkan satu cerita beserta interaksi yang sama, termasuk cerita lama di luar halaman pertama. Penghapusan dari permalink kembali ke feed. ID lintas kelas dan post terhapus ditolak. Bookmark tidak mengirim notifikasi ke penulis.

Migration bersifat tambahan dan tidak memerlukan seeder. Tes mencakup persistensi, idempotensi, privasi per pengguna, penghapusan, kelas berbeda, verifikasi email, dan permalink cerita lama. Server tidak dijalankan; pemeriksaan lewat HTTP/Livewire tests, bukan browser interaktif.
