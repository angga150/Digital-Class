# Product

## 1. Project Overview

Digital Class Universe adalah website digital untuk satu kelas mahasiswa. Tujuannya bukan membuat sistem akademik formal, melainkan membuat ruang digital yang:

- dipakai seluruh anggota kelas,
- fun dan social,
- tetap berguna untuk informasi formal,
- menyimpan aktivitas dan kenangan,
- terasa seperti tempat berkumpul digital kelas.

Project ini adalah tugas kelompok mahasiswa. Karena kemampuan tim beragam, produk dan arsitektur harus realistis: **rapi, tetapi tidak overengineered**.

## 2. Prinsip Produk

### Fun First

Motivasi utama mahasiswa adalah rasa terhubung, penasaran, dan ingin ikut dalam kehidupan kelas. Dashboard dan onboarding harus terasa hidup.

### Useful Second

Informasi praktis seperti jadwal, deadline, pengumuman, event, dan polling tetap tersedia, tetapi tidak boleh membuat aplikasi terasa seperti portal administrasi.

### Memory Forever

Foto, timeline, cerita, dan momen kelas harus mudah disimpan dan ditemukan kembali.

### Privacy First

Data pribadi tidak boleh terbuka secara default. Visibility harus dipikirkan sejak awal dan diterapkan di setiap feature.

### Community Owned

Kelas memiliki ruangnya sendiri. Member dapat membuat dan menyimpan konten tanpa proses approval untuk setiap hal kecil; moderasi dilakukan ketika ada masalah.

### Simple First

Bangun hal yang benar-benar diperlukan. Jangan menambah kompleksitas hanya untuk kemungkinan masa depan.

### Design for Scale, Build for One

MVP hanya satu kelas, tetapi database dan kode tetap menggunakan `class_id` sehingga ekspansi ke beberapa kelas tidak mengharuskan rewrite besar.

## 3. Target User

### Member

Mayoritas pengguna. Mereka ingin:

- tahu apa yang terjadi di kelas tanpa menggali chat,
- lebih mengenal teman,
- punya ruang ekspresi santai,
- menyimpan kenangan.

Fitur utama: Dashboard, Birthday, Event, Profile, Feed, Gallery, Timeline, Polling, Achievement.

### Class Admin

Ketua kelas/pengurus. Mereka membutuhkan alat untuk:

- menyampaikan informasi resmi,
- membuat event dan polling,
- mengelola member,
- mengelola konten resmi,
- menjalankan fungsi administratif yang memang sudah diaktifkan.

### Moderator

Menjaga kesehatan komunitas:

- melihat report,
- meninjau moderation queue,
- hide/restore konten,
- membuat keputusan moderasi dengan jejak audit.

### Super Admin

Pengelola platform. Di MVP UI-nya minimal, tetapi role sudah ada untuk kebutuhan platform dan ekspansi multi-class.

### Future Users

- **Dosen:** membutuhkan visibility sangat terbatas.
- **Alumni:** kemungkinan read-only untuk arsip; status membership perlu membedakan `active`, `alumni`, dan `inactive`.
- **Admin jurusan/kampus:** kelak membutuhkan layer Organization/Campus.
- **Kelas lain:** data harus tetap terisolasi berdasarkan `class_id`.

## 4. Role dan Permission

| Action | Super Admin | Class Admin | Moderator | Member | Guest |
|---|---:|---:|---:|---:|---:|
| Kelola pengaturan kelas | ✓ | ✓ | ✗ | ✗ | ✗ |
| Kelola user & role | ✓ | ✓ dalam kelas | ✗ | ✗ | ✗ |
| Buat post | ✓ | ✓ | ✓ | ✓ | ✗ |
| Pengumuman/event resmi | ✓ | ✓ | ✗ | ✗ | ✗ |
| Buat polling | ✓ | ✓ | ✗ | ✗ | ✗ |
| Vote polling | ✓ | ✓ | ✓ | ✓ | ✗ |
| Moderasi | ✓ | ✓ | ✓ | ✗ | ✗ |
| Approve/reject confession | ✓ | ✓ | ✓ | ✗ | ✗ |
| Kelola birthday sendiri | ✓ | ✓ | ✓ | ✓ sendiri | ✗ |
| Upload gallery/timeline | ✓ | ✓ | ✓ | ✓ | ✗ |
| Kelola kas | ✓ | ✓ jika bendahara | ✗ | ✗ | ✗ |
| Lihat laporan kas | ✓ | ✓ | ✗ | read-only jika publik | ✗ |
| Lihat audit log | ✓ | ✓ | ✓ | ✗ | ✗ |
| Akses lintas kelas | ✓ | ✗ | ✗ | ✗ | ✗ |

Role disimpan di `class_memberships`, bukan `users`, karena satu user dapat mempunyai role berbeda di kelas berbeda di masa depan.

`alumni` bukan role baru; ia adalah status membership. Status dapat memengaruhi permission.

## 5. Privacy & Visibility

Field sensitif:

- tanggal lahir,
- email,
- nomor telepon,
- foto pribadi,
- alamat,
- media sosial,
- data akademik,
- transaksi kas.

Level visibility:

| Level | Arti |
|---|---|
| `public` | dapat dilihat siapa saja |
| `class-only` | hanya member kelas yang login |
| `admin-only` | Class Admin dan Super Admin |
| `private` | pemilik dan Super Admin |

Default field sensitif adalah `class-only`.

Visibility menjawab **"siapa boleh melihat data?"**, sedangkan Policy menjawab **"siapa boleh melakukan aksi?"**. Keduanya harus berjalan bersama.

Contoh birthday: jika tanggal lahir `private`, sistem tetap boleh memberi tahu bahwa seseorang ulang tahun hari ini tanpa menampilkan tanggal/tahun lahirnya.

## 6. Moderation

Moderation adalah fondasi P0.

Mekanisme:

1. Member membuat report.
2. Report masuk moderation queue.
3. Moderator/Class Admin/Super Admin meninjau.
4. Konten dapat di-dismiss, di-hide, di-restore, atau dihapus sesuai kebutuhan.
5. Aksi penting dicatat dalam `audit_logs`.

Target: post, comment, confession, gallery/timeline media, dan profile yang dilaporkan.

Confession **tidak boleh** diluncurkan sebelum moderation aktif.

Prinsip anti-bullying:

- kategori penghargaan tidak boleh mempermalukan,
- kategori sensitif harus melalui voting + approval,
- achievement tidak memakai leaderboard yang mempermalukan performa rendah.

## 7. Scope MVP

### Fondasi P0

1. Role & Permission
2. Privacy & Visibility
3. Moderation dasar

### User-facing P0

1. Class Dashboard
2. Academic Corner minimal
3. Event Countdown
4. Birthday System
5. Member Profile
6. Social Feed
7. Polling/Voting
8. Class Gallery
9. Class Timeline
10. Achievement 3–5 badge dasar

### P1

- Confession
- Class Finance
- Time Capsule
- Hall of Fame
- Achievement lanjutan
- Timeline lanjutan

### P2

- On This Day
- sebagian Random/Fun Features

### P3

- Spin Wheel
- Meme Generator
- Anime Character Voting
- eksperimen Random/Fun lain satu per satu

## 8. Feature Priority

Prioritas ditentukan oleh nilai untuk kelas, frekuensi penggunaan, dependency, kompleksitas, dan risiko.

Fondasi security/privacy/moderation diprioritaskan tinggi meskipun tidak terlihat oleh user karena fitur sosial bergantung padanya.

## 9. Roadmap

### Now

- Authentication
- Membership
- Privacy
- Authorization
- Dashboard
- Birthday
- Event
- Member Profile
- Academic Corner minimal

### Next

- Feed
- Poll
- Moderation
- Gallery
- Timeline

### Later

- Achievement lanjutan
- Confession
- Class Finance
- Time Capsule
- Hall of Fame
- On This Day

### Ideas

Random/Fun Features dan eksperimen engagement lainnya.

## 10. Arah Pertumbuhan

```text
Satu kelas
  ↓
Beberapa kelas
  ↓
Satu angkatan/jurusan
  ↓
Satu fakultas/kampus
  ↓
Lintas kampus
```

Layer yang diantisipasi:

```text
Campus
  └── Organization
       └── Class
            └── Membership
                 └── User
```

Jangan membangun semua layer tersebut sekarang. Pastikan MVP tidak menutup jalannya.
