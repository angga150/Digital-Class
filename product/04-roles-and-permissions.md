# ROLES & PERMISSIONS
## Digital Class Universe

## Daftar Role

### Super Admin
Pengelola platform secara keseluruhan. Di MVP, kemungkinan besar adalah tim pembuat produk. Akses backend/config lintas kelas.

### Class Admin
Ketua kelas, wakil ketua, sekretaris, bendahara. Mengelola konten resmi, event, polling, dan (jika diaktifkan) kas kelas.

### Moderator
Bertanggung jawab menjaga kesehatan konten komunitas: report, hide, approve/reject confession.

### Member
Mahasiswa anggota kelas — mayoritas pengguna. Bisa membuat konten sosial, mengatur profil & privasi sendiri.

### Guest *(opsional, belum tentu masuk MVP)*
Pengunjung terbatas — misalnya calon anggota, alumni yang belum diverifikasi, atau demo publik.

> Detail kebutuhan & motivasi tiap role ada di [`03-target-user-analysis.md`](./03-target-user-analysis.md).

## Permission Matrix (Baseline)

| Action | Super Admin | Class Admin | Moderator | Member | Guest |
|---|---|---|---|---|---|
| Kelola pengaturan kelas | ✓ | ✓ | ✗ | ✗ | ✗ |
| Kelola user & role | ✓ | ✓ (dalam kelasnya) | ✗ | ✗ | ✗ |
| Buat post di feed | ✓ | ✓ | ✓ | ✓ | ✗ |
| Buat pengumuman/event resmi | ✓ | ✓ | ✗ | ✗ | ✗ |
| Buat polling | ✓ | ✓ | ✗ | ✗ | ✗ |
| Vote polling | ✓ | ✓ | ✓ | ✓ | ✗ |
| Moderasi konten (report/hide) | ✓ | ✓ | ✓ | ✗ | ✗ |
| Approve/reject confession | ✓ | ✓ | ✓ | ✗ | ✗ |
| Kirim confession | ✓ | ✓ | ✓ | ✓ | ✗ |
| Kelola birthday sendiri | ✓ | ✓ | ✓ | ✓ (milik sendiri) | ✗ |
| Upload ke gallery/timeline | ✓ | ✓ | ✓ | ✓ | ✗ |
| Kelola kas kelas (input transaksi) | ✓ | ✓ (role bendahara) | ✗ | ✗ | ✗ |
| Lihat laporan kas | ✓ | ✓ | ✗ | ✓ (read-only, jika publik) | ✗ |
| Lihat audit log | ✓ | ✓ | ✓ | ✗ | ✗ |
| Akses lintas kelas | ✓ | ✗ | ✗ | ✗ | ✗ |

Matrix ini adalah baseline MVP satu kelas. Kolom "Akses lintas kelas" relevan begitu produk berkembang ke multi-class/multi-tenant (lihat [`06-mvp-scope-and-roadmap.md`](./06-mvp-scope-and-roadmap.md)).

## Catatan Desain

- Role disimpan sebagai relasi pada tabel **Membership** (user ↔ class), bukan sebagai atribut tetap pada User — karena satu user bisa punya role berbeda di kelas berbeda di masa depan (multi-class).
- Alumni bukan role terpisah, melainkan **status** pada Membership (`active` / `alumni` / `inactive`) yang memengaruhi permission secara dinamis (misal: alumni read-only pada arsip, tidak bisa posting baru).
- Role Dosen (future) butuh visibility model granular per-field, bukan sekadar ditambahkan ke matrix di atas — lihat [`05-privacy-and-moderation.md`](./05-privacy-and-moderation.md).
