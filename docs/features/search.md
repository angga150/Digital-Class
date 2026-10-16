# Pencarian Kelas

Diimplementasikan 16 Oktober 2026. `/search?q=...&category=...` mencari data asli berdasarkan kategori Cerita, Pengumuman, Agenda, Polling, atau Teman. Header dashboard desktop mengirim form pencarian; tombol pencarian tersedia di HP. Hasil dipaginasi 10 per halaman dan menuju detail asli, termasuk permalink polling `/polls/{pollId}`.

Minimal 2 dan maksimal 100 karakter. Query parameter terikat dan karakter `%`, `_`, serta `!` dianggap literal, bukan wildcard pengguna. Pencarian substring dilakukan pada teks cerita, isi pengumuman, judul/deskripsi agenda, pertanyaan/deskripsi polling, atau nama lengkap anggota. Tidak mencari email, nickname, bio, tanggal lahir, komentar, atau hasil voting. Hasil polling tidak memuat jumlah suara; detail tetap mengikuti policy hasil.

Semua query menggunakan scope kelas aktif. Post/agenda terhapus tidak ditemukan. Data profil pada hasil memakai presenter privasi. Query dan kategori disimpan pada URL untuk navigasi; data hasil tidak disimpan sebagai state publik Livewire. Tidak memakai layanan pencarian eksternal, tidak memerlukan migration, dan bukan pencarian lintas kelas. Fitur typo-tolerance, ranking relevansi, serta pencarian seluruh kategori sekaligus belum tersedia.

Tes mencakup kategori/tautan detail, data privat, isolasi kelas, soft-delete, kelas dinonaktifkan, karakter literal, validasi, pagination, escaping, dan autentikasi. Server tidak dinyalakan; verifikasi dilakukan melalui HTTP/Livewire tests dan build.
