# Product

Dokumen ini menjelaskan **apa** yang kita bangun dan **kenapa** — mulai dari masalah yang diselesaikan sampai fitur apa saja yang masuk MVP. Kalau kamu baru gabung tim dan ingin paham gambaran besar project sebelum mulai coding, mulai dari sini.

## 1. Project Overview

**Digital Class Universe** adalah website digital khusus untuk satu kelas mahasiswa — tempat berkumpul digital yang menyimpan informasi, aktivitas, kenangan, dan interaksi kelas selama masa kuliah sampai setelah lulus.

Ini bukan website kelas formal yang isinya cuma jadwal dan pengumuman. Ini juga bukan media sosial penuh atau LMS (Learning Management System). Ini kombinasi ringan dari:

- **Class Hub** — pusat informasi kelas
- **Social Space** — tempat ngobrol dan berinteraksi santai
- **Digital Scrapbook** — arsip foto dan cerita kelas
- **Class Archive** — kenangan yang tersimpan permanen

Project ini juga adalah tugas kelompok mahasiswa, dikerjakan oleh tim yang sebagian besar masih belajar workflow development profesional. Karena itu, seluruh keputusan teknis di dokumentasi ini sengaja dibuat realistis untuk kemampuan tim — bukan disamakan dengan software enterprise.

## 2. Problem

Masalah nyata yang sering terjadi di kelas mahasiswa, yang jadi alasan project ini dibuat:

- Informasi kelas tersebar di WhatsApp/Telegram, gampang hilang ditelan chat
- Event/agenda sulit ditemukan, orang lupa tanggal acara
- Info akademik bercampur dengan obrolan santai
- Orang lupa ulang tahun teman sekelas
- Anggota kelas tidak terlalu saling kenal meski sudah sekelas berbulan-bulan
- Voting/polling masih manual di grup chat — sering ada yang vote dua kali, hasil hilang ditelan chat
- Foto kelas tercecer di banyak HP, hilang setelah lulus
- Tidak ada arsip perjalanan kelas dari semester ke semester
- Tidak ada tempat aman untuk menyuarakan hal sensitif secara anonim

## 3. Solution

Setiap fitur di project ini punya alasan konkret — bukan dibuat karena "terlihat keren". Ringkasan pemetaan masalah → solusi:

| Masalah | Solusi | Fitur |
|---|---|---|
| Info tersebar & hilang | Satu sumber kebenaran terpusat | Dashboard, Academic Corner |
| Event sulit ditemukan | Countdown terpusat | Event Countdown |
| Lupa ulang tahun teman | Countdown otomatis + privacy | Birthday System |
| Tidak saling kenal | Profil casual, bukan CV | Member Profile |
| Butuh ruang ekspresi santai | Feed ringan | Social Feed |
| Voting manual tidak valid | Polling dengan validasi satu-vote | Polling |
| Foto tercecer | Galeri terpusat | Class Gallery |
| Tidak ada arsip perjalanan kelas | Timeline kronologis | Class Timeline |
| Tidak ada apresiasi ringan | Badge/lencana | Achievement |
| Konten sensitif butuh moderasi | Report + queue + audit log | Moderation |

## 4. Target Users

### Member (mahasiswa anggota kelas) — pengguna utama

90%+ dari total pengguna. Motivasi mereka **emosional dulu, baru praktis** — orang buka website karena penasaran lihat feed atau ulang tahun teman, bukan karena "harus cek jadwal". Desain fitur harus menyasar sisi ini dulu.

Kebutuhan: tahu apa yang terjadi di kelas tanpa scroll WA, merasa terhubung dengan teman, ruang ekspresi santai, momen kuliah terekam.

### Class Admin (ketua kelas, pengurus)

Kebutuhan: alat untuk broadcast info resmi secara efisien, kontrol atas konten sensitif (approve confession nanti, kelola kas nanti), akuntabilitas (bukti kerja pengurusannya transparan).

### Moderator

Bisa orang yang sama dengan pengurus, atau anggota terpisah yang dipercaya khusus untuk urusan konten. Kebutuhan: alat pantau & tindak konten bermasalah dengan cepat, jejak keputusan (audit log) agar tidak dituduh subjektif.

### Super Admin

Pengelola platform secara keseluruhan — di MVP kemungkinan besar tim pembuat sendiri. Perlu ada di skema data sejak awal, tapi di MVP cukup akses backend minimal.

### Future users (belum ada di MVP, tapi memengaruhi desain data)

- **Dosen** — butuh visibility model granular per-field supaya bisa dikecualikan dari konten sosial (feed, gallery, confession) tanpa refactor besar
- **Alumni** — butuh *status keanggotaan* (bukan role tetap), karena hak akses berubah setelah lulus (misal read-only)
- **Kelas lain / jurusan / kampus** — butuh semua data di-scope per kelas sejak baris kode pertama

## 5. Product Principles

Prinsip berikut mengikat semua keputusan desain dan development:

1. **Fun First** — orang datang karena website menyenangkan
2. **Useful Second** — orang tetap kembali karena ada manfaat nyata
3. **Memory Forever** — website jadi arsip perjalanan kelas, data historis tidak boleh gampang hilang
4. **Community Owned** — konten dari dan milik bersama anggota kelas
5. **Privacy First** — data mahasiswa dilindungi, setiap info sensitif punya kontrol akses jelas
6. **Design for Scale, Build for One** — skema data tidak hardcode untuk satu kelas, tapi MVP fokus satu kelas saja
7. **System Before Feature** — fondasi (auth, role, privacy, moderation) dibangun sebelum fitur sosial apa pun
8. **Simple First** — hindari overengineering, pakai konvensi Laravel dulu sebelum bikin pattern custom
9. **Documentation as Source of Truth** — dokumentasi dan kode harus selalu sinkron
10. **Real User Value** — fitur harus punya alasan jelas untuk digunakan, bukan sekadar terlihat keren

## 6. Features & Prioritas

Skala prioritas: **P0** wajib MVP · **P1** penting pasca-MVP · **P2** nice-to-have · **P3** eksperimen masa depan.

### P0 — Wajib MVP

| Fitur | Kenapa masuk P0 |
|---|---|
| Class Dashboard | Alasan utama orang buka website tiap hari |
| Academic Corner (minimal) | Kebutuhan fungsional dasar (jadwal, deadline, pengumuman) |
| Event Countdown | Kebutuhan rutin nyata (UTS, UAS, wisuda, acara kelas) |
| Birthday System | Emosional, dipakai harian |
| Member Profile | Fondasi identitas & personalisasi fitur lain |
| Social Feed (dasar) | Mesin engagement utama |
| Polling | Kebutuhan koordinasi rutin |
| Class Gallery (dasar) | Inti dari prinsip Memory Forever |
| Class Timeline (dasar) | Fitur pembeda produk ini dari website kelas biasa |
| Achievement (3–5 badge dasar) | Murah dibangun, langsung terasa saat onboarding |
| Moderation (dasar: report + queue) | **Fondasi wajib**, dibangun bersamaan dengan Feed, bukan setelahnya |
| Role & Permission | **Fondasi wajib**, harus ada sebelum fitur apa pun |
| Privacy per-field | **Fondasi wajib**, harus ada sebelum fitur sosial apa pun |

### P1 — Pasca-MVP

| Fitur | Kenapa ditunda |
|---|---|
| Confession | Risiko bullying tinggi, butuh Moderation System matang dulu |
| Class Finance | Risiko transparansi, butuh role/permission matang dulu |
| Time Capsule | Bagus secara emosional, tapi tidak kritikal di hari-hari awal |
| Hall of Fame | Kategori sensitif butuh voting & approval dulu |
| Achievement lanjutan | Trigger lebih kompleks, butuh event-tracking lebih kaya |

### P2 — Nice to Have

- **On This Day** — resurfacing konten lama; secara teknis mudah, tapi percuma tanpa data historis minimal 1 tahun
- Sebagian Random/Fun Features (mis. Daily Question)

### P3 — Eksperimen Masa Depan

Sisa Random/Fun Features (Spin Wheel, Meme Generator, Anime Character Voting, dll.) — diuji satu per satu, bukan sekaligus.

### Anti-Feature (sengaja tidak dibangun)

Realtime chat seperti WhatsApp, video call, LMS penuh, marketplace kompleks, payment gateway, microservices, AI berlebihan, recommendation engine, enterprise analytics — semua ini di luar scope dan hanya menambah kompleksitas tanpa manfaat nyata di skala project ini.

## 7. Roles & Permission

5 role tetap, disimpan sebagai relasi pada tabel `class_memberships` (bukan atribut tetap di `users`) — karena satu user bisa punya role berbeda di kelas berbeda di masa depan, dan alumni butuh *status* yang berubah dari waktu ke waktu.

| Role | Siapa |
|---|---|
| Super Admin | Pengelola platform |
| Class Admin | Ketua/pengurus kelas |
| Moderator | Pengelola konten komunitas |
| Member | Mahasiswa biasa |
| Guest | Belum login — akses sangat terbatas |

### Permission Matrix

| Action | Super Admin | Class Admin | Moderator | Member |
|---|---|---|---|---|
| Kelola pengaturan kelas | ✓ | ✓ | ✗ | ✗ |
| Kelola user & role | ✓ | ✓ | ✗ | ✗ |
| Buat post di feed | ✓ | ✓ | ✓ | ✓ |
| Buat pengumuman/event resmi | ✓ | ✓ | ✗ | ✗ |
| Buat polling | ✓ | ✓ | ✗ | ✗ |
| Vote polling | ✓ | ✓ | ✓ | ✓ |
| Moderasi konten (report/hide) | ✓ | ✓ | ✓ | ✗ |
| Kelola birthday sendiri | ✓ | ✓ | ✓ | ✓ (milik sendiri) |
| Upload ke gallery/timeline | ✓ | ✓ | ✓ | ✓ |
| Lihat audit log | ✓ | ✓ | ✓ | ✗ |

Selain role, `class_memberships` juga punya `status` (`active` / `alumni` / `inactive`) yang ikut menentukan permission — misalnya status `alumni` nanti hanya bisa baca arsip, tidak bisa posting baru.

## 8. Privacy & Moderation

### Data Sensitif

Field berikut wajib punya kontrol visibility eksplisit, tidak boleh default terbuka: tanggal lahir (khususnya tahun lahir), email, nomor telepon, foto pribadi, alamat, akun media sosial, data transaksi kas.

### Level Visibility

| Level | Arti |
|---|---|
| `public` | Bisa dilihat siapa saja |
| `class_only` | Hanya anggota kelas yang login (default untuk field sensitif) |
| `admin_only` | Hanya Class Admin & Super Admin |
| `private` | Hanya pemilik data & Super Admin |

Contoh penerapan: tanggal lahir bisa `public`/`class_only`/`private`. Kalau `private`, sistem tetap boleh menampilkan "hari ini ulang tahun si X" tanpa membuka tanggal/tahun lahir eksaknya.

### Moderation System

- **Report** — member melaporkan konten bermasalah
- **Moderation Queue** — antrean yang cuma terlihat oleh Moderator ke atas
- **Hide/Block** — konten disembunyikan, bukan dihapus permanen (bisa direview ulang)
- **Audit Log** — jejak siapa melakukan aksi moderasi apa, kapan

Konten yang wajib bisa dimoderasi: post & comment di feed, gambar di gallery/timeline, profil (jika dilaporkan).

Confession (fitur P1) **tidak boleh diluncurkan** sebelum Moderation System aktif — ini alasan utama kenapa Confession masuk P1, bukan P0.

## 9. MVP Scope

MVP dibangun **untuk satu kelas**, tapi skema data tidak hardcode. Struktur jenjang yang diantisipasi sejak awal (belum diisi di MVP, tapi strukturnya tidak menutup jalan ke sana):

```
Campus → Faculty/Department → Class → Class Membership → User
```

Implikasi konkret: setiap tabel data (post, gallery, timeline, dll.) di-scope dengan `class_id` sejak baris kode pertama — bukan ditambahkan belakangan. Detail teknis lengkap ada di [`03-architecture.md`](./03-architecture.md) dan [`04-database.md`](./04-database.md).

## 10. Roadmap

### Now

- Role & Permission, Privacy per-field, Moderation dasar (fondasi — tidak terlihat user tapi wajib duluan)
- Authentication (registrasi via join code kelas)
- Member Profile

### Next

- Class Dashboard, Birthday System, Event Countdown, Academic Corner (minimal)
- Social Feed, Polling
- Class Gallery, Class Timeline
- Achievement dasar

### Later

- Confession (setelah Moderation matang)
- Class Finance
- Time Capsule
- Hall of Fame
- Achievement lanjutan

### Ideas (belum tentu dikerjakan)

- On This Day
- Random/Fun Features (Spin Wheel, Meme Generator, Daily Question, dll.)
- Dark mode
- Sosial login (Google, dll.)
